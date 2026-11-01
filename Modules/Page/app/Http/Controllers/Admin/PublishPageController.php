<?php

declare(strict_types=1);

namespace Modules\Page\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Modules\Page\Http\Requests\PublishPageRequest;
use Modules\Page\Models\Page;
use Modules\Page\Services\PageService;

final class PublishPageController
{
    public function __invoke(PublishPageRequest $request, Page $page, PageService $pages): RedirectResponse
    {
        $pages->publish($page, $request->publishedAt(), $request->user()?->id);

        return back();
    }
}
