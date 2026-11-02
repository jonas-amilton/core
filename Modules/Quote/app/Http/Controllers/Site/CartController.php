<?php

declare(strict_types=1);

namespace Modules\Quote\Http\Controllers\Site;

use Inertia\Inertia;
use Inertia\Response;
use Modules\Page\Services\SiteNavigation;
use Modules\Quote\Services\Cart;

final class CartController
{
    public function __invoke(Cart $cart, SiteNavigation $navigation): Response
    {
        return Inertia::render('Quote::Cart', [
            'items' => array_map(fn (array $item): array => [
                'id' => $item['product']->id,
                'name' => $item['product']->name,
                'slug' => $item['product']->slug,
                'sku' => $item['product']->sku,
                'cover' => $item['product']->cover(),
                'quantity' => $item['quantity'],
            ], $cart->items()),
            'navigation' => $navigation->items(),
        ]);
    }
}
