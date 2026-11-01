<?php

declare(strict_types=1);

namespace Modules\Page\Http\Controllers\Api;

use Modules\Page\Http\Resources\PageResource;
use Modules\Page\Models\Page;

final class ShowPageController
{
    public function __invoke(Page $page): PageResource
    {
        return new PageResource($page);
    }
}
