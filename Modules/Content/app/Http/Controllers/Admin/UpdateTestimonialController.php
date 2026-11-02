<?php

declare(strict_types=1);

namespace Modules\Content\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Modules\Content\Http\Requests\TestimonialRequest;
use Modules\Content\Models\Testimonial;
use Modules\Content\Services\ContentService;

final class UpdateTestimonialController
{
    public function __invoke(TestimonialRequest $request, Testimonial $testimonial, ContentService $content): RedirectResponse
    {
        $content->updateTestimonial($testimonial, $request->toData());

        return to_route('testimonial.index');
    }
}
