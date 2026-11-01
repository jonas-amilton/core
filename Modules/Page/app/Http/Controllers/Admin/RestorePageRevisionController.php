<?php

declare(strict_types=1);

namespace Modules\Page\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Page\Models\Page;
use Modules\Page\Models\PageRevision;
use Modules\Page\Services\PageService;

final class RestorePageRevisionController
{
    public function __invoke(Request $request, Page $page, PageRevision $revision, PageService $pages): RedirectResponse
    {
        abort_unless($revision->page_id === $page->id, 404);

        $pages->restore($page, $revision, $request->user()?->id);

        return back();
    }
}
