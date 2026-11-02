<?php

declare(strict_types=1);

namespace Modules\Media\Http\Controllers\Admin;

use Inertia\Inertia;
use Inertia\Response;
use Modules\Media\Http\Requests\StoreMediaRequest;

final class CreateMediaController
{
    public function __invoke(): Response
    {
        return Inertia::render('Media::Create', [
            'maxKilobytes' => StoreMediaRequest::maxKilobytes(),
        ]);
    }
}
