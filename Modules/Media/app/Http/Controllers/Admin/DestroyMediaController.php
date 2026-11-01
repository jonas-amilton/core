<?php

declare(strict_types=1);

namespace Modules\Media\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Modules\Media\Models\Media;
use Modules\Media\Services\MediaService;

final class DestroyMediaController
{
    public function __invoke(Media $media, MediaService $service): RedirectResponse
    {
        $service->delete($media);

        return to_route('media.index');
    }
}
