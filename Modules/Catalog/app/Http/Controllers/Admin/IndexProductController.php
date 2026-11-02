<?php

declare(strict_types=1);

namespace Modules\Catalog\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Catalog\Models\Product;
use Modules\Catalog\Services\CatalogService;

final class IndexProductController
{
    public function __invoke(Request $request, CatalogService $catalog): Response
    {
        $search = trim($request->string('search')->toString());
        $categoryId = $request->integer('category') ?: null;

        return Inertia::render('Catalog::Products', [
            'products' => $catalog->products($search !== '' ? $search : null, $categoryId)
                ->through(fn (Product $product): array => $product->toAdminArray()),
            'filters' => [
                'search' => $search,
                'category' => $categoryId,
            ],
            'categories' => $catalog->categoryOptions(),
        ]);
    }
}
