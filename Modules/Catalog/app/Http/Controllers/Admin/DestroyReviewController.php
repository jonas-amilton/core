<?php

declare(strict_types=1);

namespace Modules\Catalog\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Modules\Catalog\Models\Review;

final class DestroyReviewController
{
    public function __invoke(Review $review): RedirectResponse
    {
        $review->delete();

        return back();
    }
}
