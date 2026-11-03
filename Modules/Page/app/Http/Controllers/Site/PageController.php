<?php

declare(strict_types=1);

namespace Modules\Page\Http\Controllers\Site;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Page\Contracts\PageRepository;
use Modules\Page\Services\BlockContentResolver;
use Modules\Page\Services\SiteNavigation;
use Modules\Page\Services\SystemPageProvisioner;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

final class PageController
{
    public function __invoke(
        Request $request,
        string $slug,
        PageRepository $pages,
        SiteNavigation $navigation,
        SystemPageProvisioner $system,
        BlockContentResolver $content,
    ): SymfonyResponse {
        $page = $pages->findPublishedBySlug($slug);
        $status = 200;

        if ($page === null) {
            $page = $system->notFound();
            $status = 404;
        } elseif ($page->isNotFound()) {
            $status = 404;
        }

        $data = $page->toPublicArray();

        /** @var array<int, array<string, mixed>> $blocks */
        $blocks = is_array($data['blocks'] ?? null) ? $data['blocks'] : [];
        $data['blocks'] = $content->resolve($blocks);

        return Inertia::render('Site::Show', [
            'page' => $data,
            'navigation' => $navigation->items(),
        ])->toResponse($request)->setStatusCode($status);
    }
}
