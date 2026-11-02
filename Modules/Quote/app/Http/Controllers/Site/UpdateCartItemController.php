<?php

declare(strict_types=1);

namespace Modules\Quote\Http\Controllers\Site;

use Illuminate\Http\RedirectResponse;
use Modules\Quote\Http\Requests\UpdateCartItemRequest;
use Modules\Quote\Services\Cart;

final class UpdateCartItemController
{
    public function __invoke(int $product, UpdateCartItemRequest $request, Cart $cart): RedirectResponse
    {
        $cart->setQuantity($product, (int) $request->input('quantity'));

        return back()->with('success', 'Carrinho atualizado.');
    }
}
