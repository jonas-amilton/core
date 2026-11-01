<?php

declare(strict_types=1);

namespace Modules\Page\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Modules\Page\Models\Page;
use Modules\Page\Services\PageService;

final class DestroyPageController
{
    public function __invoke(Page $page, PageService $pages): RedirectResponse
    {
        $pages->delete($page);

        return to_route('page.index');
    }
}
