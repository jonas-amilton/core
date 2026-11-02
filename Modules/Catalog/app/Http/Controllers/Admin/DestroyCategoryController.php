<?php

declare(strict_types=1);

namespace Modules\Catalog\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Modules\Catalog\Models\Category;
use Modules\Catalog\Services\CatalogService;

final class DestroyCategoryController
{
    public function __invoke(Category $category, CatalogService $catalog): RedirectResponse
    {
        $catalog->deleteCategory($category);

        return to_route('catalog.category.index');
    }
}
