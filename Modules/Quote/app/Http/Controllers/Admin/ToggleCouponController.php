<?php

declare(strict_types=1);

namespace Modules\Quote\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Modules\Quote\Models\Coupon;

final class ToggleCouponController
{
    public function __invoke(Coupon $coupon): RedirectResponse
    {
        $coupon->update(['active' => ! $coupon->active]);

        return back();
    }
}
