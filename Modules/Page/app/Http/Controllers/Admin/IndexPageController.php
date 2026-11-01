<?php

declare(strict_types=1);

namespace Modules\Page\Http\Controllers\Admin;

use Inertia\Inertia;
use Inertia\Response;
use Modules\Page\Contracts\PageRepository;
use Modules\Page\Models\Page;
use Modules\Page\Services\SystemPageProvisioner;

final class IndexPageController
{
    public function __invoke(PageRepository $pages, SystemPageProvisioner $system): Response
    {
        $system->home();
        $system->notFound();

        return Inertia::render('Page::Index', [
            'pages' => $pages->allForAdmin()
                ->map(fn (Page $page): array => $page->toAdminArray())
                ->all(),
        ]);
    }
}
