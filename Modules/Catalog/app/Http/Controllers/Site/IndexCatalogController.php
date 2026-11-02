<?php

declare(strict_types=1);

namespace Modules\Catalog\Http\Controllers\Site;

use Inertia\Inertia;
use Inertia\Response;
use Modules\Catalog\Models\Category;
use Modules\Catalog\Models\Product;
use Modules\Catalog\Services\CatalogService;
use Modules\Page\Services\SiteNavigation;

final class IndexCatalogController
{
    public function __invoke(CatalogService $catalog, SiteNavigation $navigation): Response
    {
        return Inertia::render('Catalog::Index', [
            'products' => $catalog->products(null, null, 12)
                ->through(fn (Product $product): array => $product->toAdminArray()),
            'categories' => $catalog->categories()
                ->map(fn (Category $category): array => $category->toAdminArray())
                ->all(),
            'activeCategory' => null,
            'navigation' => $navigation->items(),
        ]);
    }
}
