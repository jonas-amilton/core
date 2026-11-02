<?php

declare(strict_types=1);

namespace Modules\Catalog\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Modules\Catalog\Http\Requests\ProductRequest;
use Modules\Catalog\Services\CatalogService;

final class StoreProductController
{
    public function __invoke(ProductRequest $request, CatalogService $catalog): RedirectResponse
    {
        $product = $catalog->createProduct($request->toData());

        return to_route('catalog.product.edit', $product);
    }
}
