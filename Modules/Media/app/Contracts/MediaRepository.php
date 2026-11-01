<?php

declare(strict_types=1);

namespace Modules\Media\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Modules\Media\Data\MediaData;
use Modules\Media\Enums\MediaKind;
use Modules\Media\Models\Media;

interface MediaRepository
{
    /**
     * @return Collection<int, Media>
     */
    public function all(?string $search = null, ?MediaKind $kind = null): Collection;

    public function find(int $id): ?Media;

    public function create(MediaData $data): Media;

    public function update(Media $media, ?string $alt, ?string $caption): Media;

    public function delete(Media $media): void;

    public function count(): int;
}
