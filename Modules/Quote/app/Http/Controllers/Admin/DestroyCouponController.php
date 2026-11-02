<?php

declare(strict_types=1);

namespace Modules\Quote\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Modules\Quote\Models\Coupon;

final class DestroyCouponController
{
    public function __invoke(Coupon $coupon): RedirectResponse
    {
        $coupon->delete();

        return back();
    }
}
