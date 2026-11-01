<?php

declare(strict_types=1);

namespace Modules\Media\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Modules\Media\Models\Media;

final class ShowMediaController
{
    public function __invoke(Media $media): JsonResponse
    {
        return response()->json(['data' => $media->toAdminArray()]);
    }
}
