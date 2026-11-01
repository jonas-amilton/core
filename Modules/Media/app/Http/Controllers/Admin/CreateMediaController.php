<?php

declare(strict_types=1);

namespace Modules\Media\Http\Controllers\Admin;

use Inertia\Inertia;
use Inertia\Response;

final class CreateMediaController
{
    public function __invoke(): Response
    {
        return Inertia::render('Media::Create', [
            'maxKilobytes' => 10240,
        ]);
    }
}
