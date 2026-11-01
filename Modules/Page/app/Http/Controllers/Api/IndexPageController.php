<?php

declare(strict_types=1);

namespace Modules\Page\Http\Controllers\Api;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Modules\Page\Contracts\PageRepository;
use Modules\Page\Http\Resources\PageResource;

final class IndexPageController
{
    public function __invoke(PageRepository $pages): AnonymousResourceCollection
    {
        return PageResource::collection($pages->allForAdmin());
    }
}
