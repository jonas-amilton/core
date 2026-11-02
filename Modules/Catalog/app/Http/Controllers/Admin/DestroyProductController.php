<?php

declare(strict_types=1);

namespace Modules\Catalog\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Modules\Catalog\Models\Product;
use Modules\Catalog\Services\CatalogService;

final class DestroyProductController
{
    public function __invoke(Product $product, CatalogService $catalog): RedirectResponse
    {
        $catalog->deleteProduct($product);

        return to_route('catalog.product.index');
    }
}
