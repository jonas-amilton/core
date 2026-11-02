<?php

declare(strict_types=1);

namespace Modules\Catalog\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Modules\Catalog\Models\Review;

final class UpdateReviewController
{
    public function __invoke(Review $review): RedirectResponse
    {
        $review->update(['approved' => ! $review->approved]);

        return back();
    }
}
