<?php

declare(strict_types=1);

namespace Modules\Store\Enums;

enum StoreKind: string
{
    case Physical = 'physical';
    case Online = 'online';

    public function label(): string
    {
        return match ($this) {
            self::Physical => 'Loja física',
            self::Online => 'Loja virtual',
        };
    }
}
