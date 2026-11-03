<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Link fields are rendered straight into href attributes. Only relative,
 * fragment, http(s), mailto and tel targets are safe there; anything else
 * (javascript:, data:, vbscript:, ...) must not be stored.
 */
final class SafeLink implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null || $value === '') {
            return;
        }

        if (! is_string($value)) {
            $fail('O link deve ser um texto.');

            return;
        }

        if (str_starts_with($value, '/') || str_starts_with($value, '#')) {
            return;
        }

        if (preg_match('#^(https?://|mailto:|tel:)#i', $value) !== 1) {
            $fail('Use um link http(s), mailto, tel, /caminho ou #ancora.');
        }
    }
}
