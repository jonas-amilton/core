<?php

declare(strict_types=1);

namespace Modules\Quote\Http\Controllers\Site;

use Illuminate\Http\RedirectResponse;
use Modules\Quote\Services\Cart;

final class DestroyCartItemController
{
    public function __invoke(int $product, Cart $cart): RedirectResponse
    {
        $cart->remove($product);

        return back();
    }
}
