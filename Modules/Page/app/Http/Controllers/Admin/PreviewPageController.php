<?php

declare(strict_types=1);

namespace Modules\Page\Http\Controllers\Admin;

use Inertia\Inertia;
use Inertia\Response;
use Modules\Page\Models\Page;

final class PreviewPageController
{
    public function __invoke(Page $page): Response
    {
        return Inertia::render('Page::Preview', [
            'page' => $page->toEditorArray(),
        ]);
    }
}
