<?php

declare(strict_types=1);

namespace Modules\Catalog\Http\Controllers\Site;

use Inertia\Inertia;
use Inertia\Response;
use Modules\Catalog\Models\Category;
use Modules\Catalog\Models\Product;
use Modules\Catalog\Services\CatalogService;
use Modules\Page\Services\SiteNavigation;

final class CategoryCatalogController
{
    public function __invoke(Category $category, CatalogService $catalog, SiteNavigation $navigation): Response
    {
        return Inertia::render('Catalog::Index', [
            'products' => $catalog->productsOfCategory($category)
                ->through(fn (Product $product): array => $product->toSiteArray()),
            'categories' => $catalog->categories()
                ->map(fn (Category $item): array => $item->toSiteArray())
                ->all(),
            'activeCategory' => $category->toSiteArray(),
            'navigation' => $navigation->items(),
        ]);
    }
}
