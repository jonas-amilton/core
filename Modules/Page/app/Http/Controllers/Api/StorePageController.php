<?php

declare(strict_types=1);

namespace Modules\Page\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Modules\Page\Http\Requests\StorePageRequest;
use Modules\Page\Http\Resources\PageResource;
use Modules\Page\Services\PageService;

final class StorePageController
{
    public function __invoke(StorePageRequest $request, PageService $pages): JsonResponse
    {
        $page = $pages->create($request->pageData());

        return (new PageResource($page))->response()->setStatusCode(201);
    }
}
