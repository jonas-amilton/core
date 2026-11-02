<?php

declare(strict_types=1);

namespace Modules\Quote\Http\Controllers\Site;

use Illuminate\Http\RedirectResponse;
use Modules\Quote\Services\Cart;

final class RemoveCouponController
{
    public function __invoke(Cart $cart): RedirectResponse
    {
        $cart->removeCoupon();

        return back()->with('success', 'Cupom removido.');
    }
}
