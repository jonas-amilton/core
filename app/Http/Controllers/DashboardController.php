<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Modules\Media\Contracts\MediaRepository;
use Modules\Page\Contracts\PageRepository;
use Modules\User\Contracts\UserRepository;

final class DashboardController extends Controller
{
    public function __invoke(
        PageRepository $pages,
        MediaRepository $media,
        UserRepository $users,
    ): Response {
        return Inertia::render('Dashboard', [
            'pagesCount' => $pages->countPublished(),
            'mediaCount' => $media->count(),
            'adminsCount' => $users->count(),
        ]);
    }
}
