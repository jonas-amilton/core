<?php

declare(strict_types=1);

namespace Modules\Page\Http\Controllers\Admin;

use Inertia\Inertia;
use Inertia\Response;

final class CreatePageController
{
    public function __invoke(): Response
    {
        return Inertia::render('Page::Editor', [
            'page' => null,
            'revisions' => [],
        ]);
    }
}
