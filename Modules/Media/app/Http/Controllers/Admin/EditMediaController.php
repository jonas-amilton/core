<?php

declare(strict_types=1);

namespace Modules\Media\Http\Controllers\Admin;

use Inertia\Inertia;
use Inertia\Response;
use Modules\Media\Models\Media;
use Modules\Media\Services\MediaService;

final class EditMediaController
{
    public function __invoke(Media $media, MediaService $service): Response
    {
        return Inertia::render('Media::Edit', [
            'media' => $media->toAdminArray(),
            'usage' => $service->usage($media),
        ]);
    }
}
