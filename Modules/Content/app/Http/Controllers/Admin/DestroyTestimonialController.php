<?php

declare(strict_types=1);

namespace Modules\Content\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Modules\Content\Models\Testimonial;
use Modules\Content\Services\ContentService;

final class DestroyTestimonialController
{
    public function __invoke(Testimonial $testimonial, ContentService $content): RedirectResponse
    {
        $content->deleteTestimonial($testimonial);

        return to_route('testimonial.index');
    }
}
