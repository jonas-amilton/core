<?php

declare(strict_types=1);

namespace Modules\Catalog\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Modules\Catalog\Http\Requests\CategoryRequest;
use Modules\Catalog\Services\CatalogService;

final class StoreCategoryController
{
    public function __invoke(CategoryRequest $request, CatalogService $catalog): RedirectResponse
    {
        $catalog->createCategory($request->toData());

        return to_route('catalog.category.index');
    }
}
