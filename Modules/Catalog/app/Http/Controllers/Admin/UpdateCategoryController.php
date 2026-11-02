<?php

declare(strict_types=1);

namespace Modules\Catalog\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Modules\Catalog\Http\Requests\CategoryRequest;
use Modules\Catalog\Models\Category;
use Modules\Catalog\Services\CatalogService;

final class UpdateCategoryController
{
    public function __invoke(CategoryRequest $request, Category $category, CatalogService $catalog): RedirectResponse
    {
        $catalog->updateCategory($category, $request->toData());

        return to_route('catalog.category.index');
    }
}
