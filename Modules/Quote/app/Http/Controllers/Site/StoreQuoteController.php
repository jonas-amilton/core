<?php

declare(strict_types=1);

namespace Modules\Quote\Http\Controllers\Site;

use Illuminate\Http\RedirectResponse;
use Modules\Quote\Http\Requests\QuoteRequest;
use Modules\Quote\Services\QuoteService;

final class StoreQuoteController
{
    public function __invoke(QuoteRequest $request, QuoteService $quotes): RedirectResponse
    {
        $quotes->submit([
            ...$request->toData(),
            'user_id' => $request->user()?->id,
        ]);

        return to_route('quote.cart')
            ->with('success', 'Orçamento enviado. Entraremos em contato em breve.');
    }
}
