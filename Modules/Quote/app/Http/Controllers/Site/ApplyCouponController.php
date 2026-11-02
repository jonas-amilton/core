<?php

declare(strict_types=1);

namespace Modules\Quote\Http\Controllers\Site;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Quote\Models\Coupon;
use Modules\Quote\Services\Cart;

final class ApplyCouponController
{
    public function __invoke(Request $request, Cart $cart): RedirectResponse
    {
        $code = mb_strtoupper(trim((string) $request->input('code')));

        $coupon = Coupon::query()->where('code', $code)->first();

        if ($coupon === null || ! $coupon->isValid()) {
            return back()->withErrors(['coupon' => __('site.coupon.invalid')]);
        }

        $cart->applyCoupon($coupon->code);

        return back()->with('success', 'Cupom aplicado.');
    }
}
