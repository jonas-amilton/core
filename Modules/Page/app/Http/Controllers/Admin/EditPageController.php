<?php

declare(strict_types=1);

namespace Modules\Page\Http\Controllers\Admin;

use Inertia\Inertia;
use Inertia\Response;
use Modules\Page\Contracts\PageRepository;
use Modules\Page\Models\Page;
use Modules\Page\Models\PageRevision;

final class EditPageController
{
    public function __invoke(Page $page, PageRepository $pages): Response
    {
        return Inertia::render('Page::Editor', [
            'page' => $page->toEditorArray(),
            'revisions' => $pages->revisions($page)
                ->map(fn (PageRevision $revision): array => $revision->toAdminArray())
                ->all(),
        ]);
    }
}
