<?php

declare(strict_types=1);

namespace Modules\Media\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Modules\Media\Http\Requests\UpdateMediaRequest;
use Modules\Media\Models\Media;
use Modules\Media\Services\MediaService;

final class UpdateMediaController
{
    public function __invoke(UpdateMediaRequest $request, Media $media, MediaService $service): JsonResponse
    {
        $updated = $service->update(
            $media,
            $request->string('alt')->toString() ?: null,
            $request->string('caption')->toString() ?: null,
        );

        return response()->json(['data' => $updated->toAdminArray()]);
    }
}
