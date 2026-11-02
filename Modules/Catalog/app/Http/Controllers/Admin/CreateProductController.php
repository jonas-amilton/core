<?php

declare(strict_types=1);

namespace Modules\Catalog\Http\Controllers\Admin;

use Inertia\Inertia;
use Inertia\Response;
use Modules\Catalog\Services\CatalogService;

final class CreateProductController
{
    public function __invoke(CatalogService $catalog): Response
    {
        return Inertia::render('Catalog::Editor', [
            'product' => null,
            'categories' => $catalog->categoryOptions(),
            'related' => $catalog->productOptions(),
        ]);
    }
}
