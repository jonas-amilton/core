<?php

declare(strict_types=1);

namespace Modules\Content\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Modules\Content\Models\Faq;
use Modules\Content\Services\ContentService;

final class DestroyFaqController
{
    public function __invoke(Faq $faq, ContentService $content): RedirectResponse
    {
        $content->deleteFaq($faq);

        return to_route('faq.index');
    }
}
