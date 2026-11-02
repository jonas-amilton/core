<?php

declare(strict_types=1);

namespace Modules\Quote\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Modules\Quote\Http\Requests\CouponRequest;
use Modules\Quote\Models\Coupon;

final class StoreCouponController
{
    public function __invoke(CouponRequest $request): RedirectResponse
    {
        Coupon::query()->create([
            'code' => mb_strtoupper(trim((string) $request->input('code'))),
            'percent' => (int) $request->input('percent'),
            'expires_at' => $request->input('expires_at') ?: null,
        ]);

        return back();
    }
}
