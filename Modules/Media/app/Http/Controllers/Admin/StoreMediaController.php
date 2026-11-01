<?php

declare(strict_types=1);

namespace Modules\Media\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Modules\Media\Http\Requests\StoreMediaRequest;
use Modules\Media\Services\MediaService;

final class StoreMediaController
{
    public function __invoke(StoreMediaRequest $request, MediaService $service): RedirectResponse
    {
        /** @var UploadedFile $file */
        $file = $request->file('file');

        $media = $service->upload($file, $request->user()?->id);

        $service->update(
            $media,
            $request->string('alt')->toString() ?: null,
            $request->string('caption')->toString() ?: null,
        );

        return to_route('media.index');
    }
}
