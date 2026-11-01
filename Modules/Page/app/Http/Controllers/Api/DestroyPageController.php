<?php

declare(strict_types=1);

namespace Modules\Page\Http\Controllers\Api;

use Illuminate\Http\Response;
use Modules\Page\Models\Page;
use Modules\Page\Services\PageService;

final class DestroyPageController
{
    public function __invoke(Page $page, PageService $pages): Response
    {
        $pages->delete($page);

        return response()->noContent();
    }
}
