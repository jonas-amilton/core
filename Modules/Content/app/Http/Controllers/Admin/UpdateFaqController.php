<?php

declare(strict_types=1);

namespace Modules\Content\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Modules\Content\Http\Requests\FaqRequest;
use Modules\Content\Models\Faq;
use Modules\Content\Services\ContentService;

final class UpdateFaqController
{
    public function __invoke(FaqRequest $request, Faq $faq, ContentService $content): RedirectResponse
    {
        $content->updateFaq($faq, $request->toData());

        return to_route('faq.index');
    }
}
