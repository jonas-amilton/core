<?php

declare(strict_types=1);

namespace Modules\Catalog\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Modules\Catalog\Http\Requests\ProductRequest;
use Modules\Catalog\Models\Product;
use Modules\Catalog\Services\CatalogService;

final class UpdateProductController
{
    public function __invoke(ProductRequest $request, Product $product, CatalogService $catalog): RedirectResponse
    {
        $catalog->updateProduct($product, $request->toData());

        return to_route('catalog.product.edit', $product);
    }
}
