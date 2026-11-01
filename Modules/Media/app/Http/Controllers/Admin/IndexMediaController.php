<?php

declare(strict_types=1);

namespace Modules\Media\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Media\Contracts\MediaRepository;
use Modules\Media\Enums\MediaKind;
use Modules\Media\Models\Media;

final class IndexMediaController
{
    public function __invoke(Request $request, MediaRepository $media): Response
    {
        $search = trim($request->string('search')->toString());
        $kind = MediaKind::tryFrom($request->string('kind')->toString());

        return Inertia::render('Media::Index', [
            'media' => $media->all($search !== '' ? $search : null, $kind)
                ->map(fn (Media $item): array => $item->toAdminArray())
                ->all(),
            'filters' => [
                'search' => $search,
                'kind' => $kind?->value,
            ],
            'kinds' => array_map(
                fn (MediaKind $case): array => ['value' => $case->value, 'label' => $case->label()],
                MediaKind::cases(),
            ),
        ]);
    }
}
