<?php

declare(strict_types=1);

namespace Modules\Content\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Modules\Content\Http\Requests\TestimonialRequest;
use Modules\Content\Services\ContentService;

final class StoreTestimonialController
{
    public function __invoke(TestimonialRequest $request, ContentService $content): RedirectResponse
    {
        $content->createTestimonial($request->toData());

        return to_route('testimonial.index');
    }
}
