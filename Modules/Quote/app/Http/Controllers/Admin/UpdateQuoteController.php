<?php

declare(strict_types=1);

namespace Modules\Quote\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Quote\Models\Quote;
use Modules\Quote\Services\QuoteService;

final class UpdateQuoteController
{
    public function __invoke(Request $request, Quote $quote, QuoteService $quotes): RedirectResponse
    {
        $quotes->markAnswered($quote, $request->boolean('answered'));

        return back();
    }
}
