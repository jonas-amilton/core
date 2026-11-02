<?php

declare(strict_types=1);

namespace Modules\Catalog\Http\Controllers\Admin;

use Inertia\Inertia;
use Inertia\Response;
use Modules\Catalog\Models\Review;

final class IndexReviewController
{
    public function __invoke(): Response
    {
        return Inertia::render('Catalog::Reviews', [
            'reviews' => Review::query()
                ->with('product')
                ->latest('id')
                ->get()
                ->map(fn (Review $review): array => $review->toAdminArray())
                ->all(),
        ]);
    }
}
