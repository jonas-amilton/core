<?php

declare(strict_types=1);

namespace Modules\Catalog\Http\Controllers\Site;

use Illuminate\Http\RedirectResponse;
use Modules\Catalog\Http\Requests\ReviewRequest;
use Modules\Catalog\Models\Product;

final class StoreReviewController
{
    public function __invoke(Product $product, ReviewRequest $request): RedirectResponse
    {
        $product->reviews()->create([
            'user_id' => $request->user()?->id,
            'name' => trim((string) $request->input('name')),
            'email' => mb_strtolower(trim((string) $request->input('email'))),
            'rating' => (int) $request->input('rating'),
            'body' => trim((string) $request->input('body')),
            'approved' => false,
        ]);

        return back()->with('review_status', 'Avaliação enviada para moderação.');
    }
}
