<?php

declare(strict_types=1);

namespace Modules\Page\Http\Controllers\Admin;

use Inertia\Inertia;
use Inertia\Response;
use Modules\Page\Models\Page;
use Modules\Page\Services\BlockContentResolver;

final class PreviewPageController
{
    public function __invoke(Page $page, BlockContentResolver $content): Response
    {
        $data = $page->toEditorArray();

        /** @var array<int, array<string, mixed>> $blocks */
        $blocks = is_array($data['blocks'] ?? null) ? $data['blocks'] : [];
        $data['blocks'] = $content->resolve($blocks);

        return Inertia::render('Page::Preview', [
            'page' => $data,
        ]);
    }
}
