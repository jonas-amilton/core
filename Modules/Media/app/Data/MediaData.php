<?php

declare(strict_types=1);

namespace Modules\Media\Data;

final readonly class MediaData
{
    public function __construct(
        public string $disk,
        public string $path,
        public string $originalName,
        public string $mime,
        public int $size,
        public ?int $width = null,
        public ?int $height = null,
        public ?string $hash = null,
        public ?int $uploadedBy = null,
    ) {}
}
