<?php

declare(strict_types=1);

namespace Modules\Catalog\Http\Controllers\Site;

use Inertia\Inertia;
use Inertia\Response;
use Modules\Catalog\Models\Product;
use Modules\Page\Services\SiteNavigation;

final class ShowProductController
{
    public function __invoke(Product $product, SiteNavigation $navigation): Response
    {
        $product->load(['media', 'categories', 'related.media']);

        return Inertia::render('Catalog::Show', [
            'product' => $product->toPublicArray(),
            'related' => $product->related
                ->map(fn (Product $item): array => $item->toCardArray())
                ->values()
                ->all(),
            'navigation' => $navigation->items(),
        ]);
    }
}
