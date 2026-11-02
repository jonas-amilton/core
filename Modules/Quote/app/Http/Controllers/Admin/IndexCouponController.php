<?php

declare(strict_types=1);

namespace Modules\Quote\Http\Controllers\Admin;

use Inertia\Inertia;
use Inertia\Response;
use Modules\Quote\Models\Coupon;

final class IndexCouponController
{
    public function __invoke(): Response
    {
        return Inertia::render('Quote::Coupons', [
            'coupons' => Coupon::query()
                ->latest('id')
                ->get()
                ->map(fn (Coupon $coupon): array => $coupon->toAdminArray())
                ->all(),
        ]);
    }
}
