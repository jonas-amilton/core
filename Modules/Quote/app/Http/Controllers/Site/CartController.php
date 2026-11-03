<?php

declare(strict_types=1);

namespace Modules\Quote\Http\Controllers\Site;

use Inertia\Inertia;
use Inertia\Response;
use Modules\Page\Services\SiteNavigation;
use Modules\Quote\Models\Coupon;
use Modules\Quote\Services\Cart;

final class CartController
{
    public function __invoke(Cart $cart, SiteNavigation $navigation): Response
    {
        $items = array_map(fn (array $item): array => [
            'id' => $item['product']->id,
            'name' => $item['product']->name,
            'slug' => $item['product']->slug,
            'sku' => $item['product']->sku,
            'price' => $item['product']->price,
            'cover' => $item['product']->cover(),
            'quantity' => $item['quantity'],
        ], $cart->items());

        $subtotal = array_sum(array_map(
            fn (array $item): float => (float) ($item['price'] ?? 0) * $item['quantity'],
            $items,
        ));

        $coupon = $cart->couponCode() !== null
            ? Coupon::query()->where('code', $cart->couponCode())->first()
            : null;

        $discount = $coupon !== null && $coupon->isValid()
            ? $coupon->discountFor($subtotal)
            : 0.0;

        return Inertia::render('Site::Cart', [
            'items' => $items,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'coupon' => $discount > 0 && $coupon !== null
                ? ['code' => $coupon->code, 'percent' => $coupon->percent]
                : null,
            'navigation' => $navigation->items(),
        ]);
    }
}
