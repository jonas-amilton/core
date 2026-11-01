<?php

declare(strict_types=1);

namespace Modules\Media\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\Media\Contracts\MediaRepository;
use Modules\Media\Data\MediaData;
use Modules\Media\Enums\MediaKind;
use Modules\Media\Models\Media;

final class EloquentMediaRepository implements MediaRepository
{
    /**
     * @return Collection<int, Media>
     */
    public function all(?string $search = null, ?MediaKind $kind = null): Collection
    {
        return Media::query()
            ->when($search !== null && $search !== '', fn (Builder $query): Builder => $query
                ->where(fn (Builder $inner): Builder => $inner
                    ->where('original_name', 'like', "%{$search}%")
                    ->orWhere('alt', 'like', "%{$search}%")))
            ->when($kind === MediaKind::Image, fn (Builder $query): Builder => $query
                ->where('mime', 'like', 'image/%'))
            ->when($kind === MediaKind::Document, fn (Builder $query): Builder => $query
                ->where('mime', 'application/pdf'))
            ->when($kind === MediaKind::Other, fn (Builder $query): Builder => $query
                ->where('mime', 'not like', 'image/%')
                ->where('mime', '!=', 'application/pdf'))
            ->latest()
            ->get();
    }

    public function find(int $id): ?Media
    {
        return Media::query()->find($id);
    }

    public function create(MediaData $data): Media
    {
        return Media::query()->create([
            'disk' => $data->disk,
            'path' => $data->path,
            'original_name' => $data->originalName,
            'mime' => $data->mime,
            'size' => $data->size,
            'width' => $data->width,
            'height' => $data->height,
            'hash' => $data->hash,
            'uploaded_by' => $data->uploadedBy,
        ]);
    }

    public function update(Media $media, ?string $alt, ?string $caption): Media
    {
        $media->update([
            'alt' => $alt,
            'caption' => $caption,
        ]);

        return $media->refresh();
    }

    public function delete(Media $media): void
    {
        $media->delete();
    }

    public function count(): int
    {
        return Media::query()->count();
    }
}
