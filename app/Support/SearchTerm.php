<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Str;

/**
 * Folding for accent-insensitive LIKE search (pt-BR users type "resistencia").
 */
final class SearchTerm
{
    public static function fold(string $text): string
    {
        return Str::lower(Str::ascii($text));
    }
}
