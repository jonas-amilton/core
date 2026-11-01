<?php

declare(strict_types=1);

namespace Modules\Media\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
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
            mime: (string) $file->getClientMimeType(),
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
                'media' => "Este arquivo está sendo usado em {$usage} página(s) e não pode ser excluído.",
            ]);
        }

        Storage::disk($media->disk)->delete($media->path);
        $this->media->delete($media);
    }

    public function usage(Media $media): int
    {
        return $this->pages->countUsingMedia($media->id);
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
