<?php

declare(strict_types=1);

namespace Modules\Page\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Modules\Page\Http\Requests\StorePageRequest;
use Modules\Page\Services\PageService;

final class StorePageController
{
    public function __invoke(StorePageRequest $request, PageService $pages): RedirectResponse
    {
        $page = $pages->create($request->pageData());

        return to_route('page.edit', $page);
    }
}
