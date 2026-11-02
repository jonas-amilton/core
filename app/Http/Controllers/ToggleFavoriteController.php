<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Modules\Catalog\Models\Product;

final class ToggleFavoriteController
{
    public function __invoke(Product $product): RedirectResponse
    {
        auth()->user()?->favorites()->toggle($product->id);

        return back();
    }
}
