<?php

declare(strict_types=1);

namespace Modules\Catalog\Http\Controllers\Admin;

use Inertia\Inertia;
use Inertia\Response;
use Modules\Catalog\Models\Category;
use Modules\Catalog\Services\CatalogService;

final class IndexCategoryController
{
    public function __invoke(CatalogService $catalog): Response
    {
        return Inertia::render('Catalog::Categories', [
            'categories' => $catalog->categories()
                ->map(fn (Category $category): array => $category->toAdminArray())
                ->all(),
            'parents' => $catalog->categoryOptions(),
        ]);
    }
}
