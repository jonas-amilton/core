<?php

declare(strict_types=1);

namespace Modules\Page\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Modules\Page\Models\Page;
use Modules\Page\Services\PageService;

final class UnpublishPageController
{
    public function __invoke(Page $page, PageService $pages): RedirectResponse
    {
        $pages->unpublish($page);

        return back();
    }
}
