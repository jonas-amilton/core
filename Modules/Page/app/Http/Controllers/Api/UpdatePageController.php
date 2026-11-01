<?php

declare(strict_types=1);

namespace Modules\Page\Http\Controllers\Api;

use Modules\Page\Http\Requests\UpdatePageRequest;
use Modules\Page\Http\Resources\PageResource;
use Modules\Page\Models\Page;
use Modules\Page\Services\PageService;

final class UpdatePageController
{
    public function __invoke(UpdatePageRequest $request, Page $page, PageService $pages): PageResource
    {
        return new PageResource($pages->update($page, $request->pageData()));
    }
}
