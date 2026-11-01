<?php

declare(strict_types=1);

namespace Modules\Media\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Modules\Media\Contracts\MediaRepository;
use Modules\Media\Models\Media;

final class IndexMediaController
{
    public function __invoke(MediaRepository $media): JsonResponse
    {
        return response()->json([
            'data' => $media->all()
                ->map(fn (Media $item): array => $item->toAdminArray())
                ->all(),
        ]);
    }
}
