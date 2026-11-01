<?php

declare(strict_types=1);

namespace Modules\Media\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Media\Contracts\MediaRepository;
use Modules\Media\Enums\MediaKind;
use Modules\Media\Models\Media;

/**
 * Lightweight JSON feed used by the page editor's image block picker.
 */
final class PickerMediaController
{
    public function __invoke(Request $request, MediaRepository $media): JsonResponse
    {
        $search = trim($request->string('search')->toString());

        $items = $media->all($search !== '' ? $search : null, MediaKind::Image)
            ->map(fn (Media $item): array => [
                'id' => $item->id,
                'url' => $item->url(),
                'alt' => $item->alt,
                'original_name' => $item->original_name,
            ])
            ->values()
            ->all();

        return response()->json(['data' => $items]);
    }
}
