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
        $product->load([
            'media',
            'categories',
            'related.media',
            'reviews' => fn ($query) => $query->where('approved', true)->latest(),
        ]);

        return Inertia::render('Catalog::Show', [
            'product' => $product->toPublicArray(),
            'related' => $product->related
                ->map(fn (Product $item): array => $item->toCardArray())
                ->values()
                ->all(),
            'reviews' => $product->reviews
                ->map(fn (Review $review): array => $review->toSiteArray())
                ->values()
                ->all(),
            'isFavorite' => $request->user()?->favorites()->whereKey($product->id)->exists() ?? false,
            'navigation' => $navigation->items(),
        ]);
    }
}
