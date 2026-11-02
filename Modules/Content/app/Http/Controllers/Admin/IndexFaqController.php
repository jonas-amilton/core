<?php

declare(strict_types=1);

namespace Modules\Content\Http\Controllers\Admin;

use Inertia\Inertia;
use Inertia\Response;
use Modules\Content\Models\Faq;
use Modules\Content\Services\ContentService;

final class IndexFaqController
{
    public function __invoke(ContentService $content): Response
    {
        return Inertia::render('Content::Faqs', [
            'faqs' => $content->faqs()
                ->map(fn (Faq $faq): array => $faq->toAdminArray())
                ->all(),
        ]);
    }
}
