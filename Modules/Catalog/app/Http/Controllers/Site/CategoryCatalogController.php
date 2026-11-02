<?php

declare(strict_types=1);

namespace Modules\Catalog\Http\Controllers\Site;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Catalog\Models\Category;
use Modules\Catalog\Models\Product;
use Modules\Catalog\Services\CatalogService;
use Modules\Page\Services\SiteNavigation;

final class CategoryCatalogController
{
    public function __invoke(Category $category, Request $request, CatalogService $catalog, SiteNavigation $navigation): Response
    {
        return Inertia::render('Catalog::Index', [
            'products' => $catalog->productsOfCategory(
                $category,
                12,
                (string) $request->query('sort', 'default'),
                $request->filled('min') ? (float) $request->query('min') : null,
                $request->filled('max') ? (float) $request->query('max') : null,
            )->through(fn (Product $product): array => $product->toSiteArray()),
            'categories' => $catalog->categories()
                ->map(fn (Category $item): array => $item->toSiteArray())
                ->all(),
            'activeCategory' => $category->toSiteArray(),
            'filters' => [
                'sort' => (string) $request->query('sort', 'default'),
                'min' => $request->query('min'),
                'max' => $request->query('max'),
            ],
            'navigation' => $navigation->items(),
        ]);
    }
}
