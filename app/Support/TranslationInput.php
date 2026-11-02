<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Reads the `translations` payload sent by the admin editors and keeps only
 * locales that carry at least one non-empty value.
 */
final class TranslationInput
{
    /**
     * @param  array<int, string>  $fields
     * @return array<string, array<string, string>>
     */
    public static function fromArray(mixed $input, array $fields): array
    {
        if (! is_array($input)) {
            return [];
        }

        $translations = [];

        foreach (['en', 'es'] as $locale) {
            $values = is_array($input[$locale] ?? null) ? $input[$locale] : [];
            $entry = [];

            foreach ($fields as $field) {
                $value = $values[$field] ?? null;

                if (is_string($value) && trim($value) !== '') {
                    $entry[$field] = trim($value);
                }
            }

            if ($entry !== []) {
                $translations[$locale] = $entry;
            }
        }

        return $translations;
    }
}
