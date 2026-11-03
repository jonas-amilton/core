<?php

declare(strict_types=1);

namespace Modules\Media\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Modules\Blog\Models\Post;
use Modules\Catalog\Models\Product;
use Modules\Media\Contracts\MediaRepository;
use Modules\Media\Data\MediaData;
use Modules\Media\Models\Media;
use Modules\Page\Contracts\PageRepository;

final class MediaService
{
    public const DISK = 'public';

    public function __construct(
        private MediaRepository $media,
        private PageRepository $pages,
    ) {}

    public function upload(UploadedFile $file, ?int $userId): Media
    {
        $extension = $file->guessExtension() ?: 'bin';
        $path = $file->storeAs('media', Str::uuid()->toString().'.'.$extension, self::DISK);

        if (! is_string($path) || $path === '') {
            throw ValidationException::withMessages([
                'file' => 'Não foi possível salvar o arquivo enviado.',
            ]);
        }

        [$width, $height] = $this->dimensions($file);

        return $this->media->create(new MediaData(
            disk: self::DISK,
            path: $path,
            originalName: $file->getClientOriginalName(),
            mime: $file->getMimeType() ?? (string) $file->getClientMimeType(),
            size: (int) $file->getSize(),
            width: $width,
            height: $height,
            hash: $this->hash($file),
            uploadedBy: $userId,
        ));
    }

    public function update(Media $media, ?string $alt, ?string $caption): Media
    {
        return $this->media->update($media, $alt, $caption);
    }

    public function delete(Media $media): void
    {
        $usage = $this->usage($media);

        if ($usage > 0) {
            throw ValidationException::withMessages([
                'media' => "Este arquivo está em uso em {$usage} conteúdo(s) do site e não pode ser excluído.",
            ]);
        }

        // Row first: if this fails, the file is still referenced. The other
        // order leaves a record pointing at a file that no longer exists.
        $this->media->delete($media);
        Storage::disk($media->disk)->delete($media->path);
    }

    public function usage(Media $media): int
    {
        // Pages keep media ids inside their blocks; products attach media
        // through a pivot and blog covers store the URL as a plain string.
        return $this->pages->countUsingMedia($media->id)
            + Product::query()->whereHas('media', fn (Builder $query): Builder => $query->whereKey($media->id))->count()
            + Post::query()->where('cover', $media->url())->count();
    }

    /**
     * @return array{0: int|null, 1: int|null}
     */
    private function dimensions(UploadedFile $file): array
    {
        $path = $file->getRealPath();
        $size = $path !== false ? @getimagesize($path) : false;

        if ($size === false) {
            return [null, null];
        }

        return [(int) $size[0], (int) $size[1]];
    }

    private function hash(UploadedFile $file): ?string
    {
        $path = $file->getRealPath();

        if ($path === false) {
            return null;
        }

        $hash = hash_file('sha256', $path);

        return is_string($hash) ? $hash : null;
    }
}
