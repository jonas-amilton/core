<?php

declare(strict_types=1);

namespace Modules\Catalog\Http\Controllers\Site;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Catalog\Models\Product;
use Modules\Catalog\Models\Review;
use Modules\Page\Services\SiteNavigation;

final class ShowProductController
{
    public function __invoke(Product $product, Request $request, SiteNavigation $navigation): Response
    {
        /** @var array<int, int> $recentIds */
        $recentIds = session()->get('recently_viewed', []);

        session()->put(
            'recently_viewed',
            collect([$product->id, ...$recentIds])->unique()->take(8)->values()->all(),
        );

        $recentlyViewed = Product::query()
            ->with(['media', 'categories'])
            ->whereIn('id', array_values(array_diff($recentIds, [$product->id])))
            ->get()
            ->sortBy(fn (Product $item): int => (int) array_search($item->id, $recentIds, true))
            ->map(fn (Product $item): array => $item->toSiteArray())
            ->values()
            ->all();

        $product->load([
            'media',
            'categories',
            'related.media',
            'reviews' => fn ($query) => $query->where('approved', true)->latest(),
        ]);

        return Inertia::render('Catalog::Show', [
            'product' => $product->toPublicArray(),
            'related' => $product->related
                ->map(fn (Product $item): array => $item->toSiteArray())
                ->values()
                ->all(),
            'recentlyViewed' => $recentlyViewed,
            'reviews' => $product->reviews
                ->map(fn (Review $review): array => $review->toSiteArray())
                ->values()
                ->all(),
            'isFavorite' => $request->user()?->favorites()->whereKey($product->id)->exists() ?? false,
            'navigation' => $navigation->items(),
        ]);
    }
}
