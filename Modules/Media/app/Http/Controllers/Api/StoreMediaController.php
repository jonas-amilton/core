<?php

declare(strict_types=1);

namespace Modules\Media\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Modules\Media\Http\Requests\StoreMediaRequest;
use Modules\Media\Services\MediaService;

final class StoreMediaController
{
    public function __invoke(StoreMediaRequest $request, MediaService $service): JsonResponse
    {
        /** @var UploadedFile $file */
        $file = $request->file('file');

        $media = $service->upload($file, $request->user()?->id);

        return response()->json(['data' => $media->toAdminArray()], 201);
    }
}
