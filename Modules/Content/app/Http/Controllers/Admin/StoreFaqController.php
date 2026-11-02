<?php

declare(strict_types=1);

namespace Modules\Content\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Modules\Content\Http\Requests\FaqRequest;
use Modules\Content\Services\ContentService;

final class StoreFaqController
{
    public function __invoke(FaqRequest $request, ContentService $content): RedirectResponse
    {
        $content->createFaq($request->toData());

        return to_route('faq.index');
    }
}
