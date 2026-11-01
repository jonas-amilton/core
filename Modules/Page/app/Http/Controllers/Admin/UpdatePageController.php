<?php

declare(strict_types=1);

namespace Modules\Page\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Modules\Page\Http\Requests\UpdatePageRequest;
use Modules\Page\Models\Page;
use Modules\Page\Services\PageService;

final class UpdatePageController
{
    public function __invoke(UpdatePageRequest $request, Page $page, PageService $pages): RedirectResponse
    {
        $pages->update($page, $request->pageData());

        return back();
    }
}
