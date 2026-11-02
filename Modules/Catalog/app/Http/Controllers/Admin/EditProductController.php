<?php

declare(strict_types=1);

namespace Modules\Catalog\Http\Controllers\Admin;

use Inertia\Inertia;
use Inertia\Response;
use Modules\Catalog\Models\Product;
use Modules\Catalog\Services\CatalogService;

final class EditProductController
{
    public function __invoke(Product $product, CatalogService $catalog): Response
    {
        return Inertia::render('Catalog::Editor', [
            'product' => $product->load(['media', 'categories', 'related'])->toEditorArray(),
            'categories' => $catalog->categoryOptions(),
            'related' => $catalog->productOptions(),
        ]);
    }
}
