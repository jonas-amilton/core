<?php

declare(strict_types=1);

namespace Modules\Media\Http\Controllers\Api;

use Illuminate\Http\Response;
use Modules\Media\Models\Media;
use Modules\Media\Services\MediaService;

final class DestroyMediaController
{
    public function __invoke(Media $media, MediaService $service): Response
    {
        $service->delete($media);

        return response()->noContent();
    }
}
