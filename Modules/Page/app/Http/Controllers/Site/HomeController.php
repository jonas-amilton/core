<?php

declare(strict_types=1);

namespace Modules\Page\Http\Controllers\Site;

use Inertia\Inertia;
use Inertia\Response;
use Modules\Page\Services\SiteNavigation;
use Modules\Page\Services\SystemPageProvisioner;

final class HomeController
{
    public function __invoke(SystemPageProvisioner $system, SiteNavigation $navigation): Response
    {
        return Inertia::render('Page::Show', [
            'page' => $system->home()->toPublicArray(),
            'navigation' => $navigation->items(),
        ]);
    }
}
