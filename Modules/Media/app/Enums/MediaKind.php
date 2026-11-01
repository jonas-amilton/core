<?php

declare(strict_types=1);

namespace Modules\Media\Enums;

enum MediaKind: string
{
    case Image = 'image';
    case Document = 'document';
    case Other = 'other';

    public static function fromMime(string $mime): self
    {
        return match (true) {
            str_starts_with($mime, 'image/') => self::Image,
            $mime === 'application/pdf' => self::Document,
            default => self::Other,
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::Image => 'Imagem',
            self::Document => 'Documento',
            self::Other => 'Outro',
        };
    }
}
