<?php

declare(strict_types=1);

namespace Modules\Quote\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Modules\Quote\Models\Quote;
use Modules\Quote\Services\QuoteService;

final class DestroyQuoteController
{
    public function __invoke(Quote $quote, QuoteService $quotes): RedirectResponse
    {
        $quotes->delete($quote);

        return to_route('quote.index');
    }
}
