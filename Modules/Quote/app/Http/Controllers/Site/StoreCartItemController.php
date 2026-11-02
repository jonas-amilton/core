<?php

declare(strict_types=1);

namespace Modules\Quote\Http\Controllers\Site;

use Illuminate\Http\RedirectResponse;
use Modules\Quote\Http\Requests\CartItemRequest;
use Modules\Quote\Services\Cart;

final class StoreCartItemController
{
    public function __invoke(CartItemRequest $request, Cart $cart): RedirectResponse
    {
        $cart->add(
            (int) $request->input('product_id'),
            (int) $request->input('quantity', 1),
        );

        return back()->with('success', 'Produto adicionado ao orçamento.');
    }
}
