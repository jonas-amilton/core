<?php

declare(strict_types=1);

namespace Modules\Page\Http\Controllers\Site;

use Inertia\Inertia;
use Inertia\Response;
use Modules\Page\Services\BlockContentResolver;
use Modules\Page\Services\SiteNavigation;
use Modules\Page\Services\SystemPageProvisioner;

final class HomeController
{
    public function __invoke(
        SystemPageProvisioner $system,
        SiteNavigation $navigation,
        BlockContentResolver $content,
    ): Response {
        $page = $system->home()->toPublicArray();

        /** @var array<int, array<string, mixed>> $blocks */
        $blocks = is_array($page['blocks'] ?? null) ? $page['blocks'] : [];
        $page['blocks'] = $content->resolve($blocks);

        return Inertia::render('Page::Show', [
            'page' => $page,
            'navigation' => $navigation->items(),
        ]);
    }
}
