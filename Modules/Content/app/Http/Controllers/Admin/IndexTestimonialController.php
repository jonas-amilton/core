<?php

declare(strict_types=1);

namespace Modules\Content\Http\Controllers\Admin;

use Inertia\Inertia;
use Inertia\Response;
use Modules\Content\Models\Testimonial;
use Modules\Content\Services\ContentService;

final class IndexTestimonialController
{
    public function __invoke(ContentService $content): Response
    {
        return Inertia::render('Content::Testimonials', [
            'testimonials' => $content->testimonials()
                ->map(fn (Testimonial $testimonial): array => $testimonial->toAdminArray())
                ->all(),
        ]);
    }
}
