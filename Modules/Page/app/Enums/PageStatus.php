<?php

declare(strict_types=1);

namespace Modules\Page\Enums;

enum PageStatus: string
{
    case Draft = 'draft';
    case Published = 'published';
    case Scheduled = 'scheduled';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Rascunho',
            self::Published => 'Publicado',
            self::Scheduled => 'Agendado',
        };
    }

    public function isVisible(): bool
    {
        return $this !== self::Draft;
    }
}
