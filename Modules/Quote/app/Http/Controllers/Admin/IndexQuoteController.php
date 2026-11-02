<?php

declare(strict_types=1);

namespace Modules\Quote\Http\Controllers\Admin;

use Inertia\Inertia;
use Inertia\Response;
use Modules\Quote\Models\Quote;
use Modules\Quote\Services\QuoteService;

final class IndexQuoteController
{
    public function __invoke(QuoteService $quotes): Response
    {
        return Inertia::render('Quote::Index', [
            'quotes' => $quotes->quotes()
                ->map(fn (Quote $quote): array => $quote->toAdminArray())
                ->all(),
        ]);
    }
}
