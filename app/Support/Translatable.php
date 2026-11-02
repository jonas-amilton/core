<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Public-site translations stored in a `translations` JSON column shaped as
 * `{"en": {"title": "..."}}`. The base column stays in pt-BR and is the
 * fallback whenever a locale has no value.
 */
trait Translatable
{
    public function trans(string $key, ?string $locale = null): mixed
    {
        $locale ??= (string) app()->getLocale();
        $translations = is_array($this->translations ?? null) ? $this->translations : [];
        $value = $translations[$locale][$key] ?? null;

        if (is_array($value)) {
            return $value !== [] ? $value : $this->getAttribute($key);
        }

        return is_string($value) && trim($value) !== ''
            ? $value
            : $this->getAttribute($key);
    }

    public function transString(string $key, ?string $locale = null): ?string
    {
        $value = $this->trans($key, $locale);

        return is_string($value) ? $value : null;
    }

    /**
     * Raw per-language values for the admin editors, without the pt-BR
     * fallback so an empty tab stays empty until the user writes it.
     *
     * @param  array<int, string>  $fields
     * @return array<string, array<string, string|null>>
     */
    public function editorTranslations(array $fields): array
    {
        $translations = is_array($this->translations ?? null) ? $this->translations : [];
        $values = [];

        foreach (['en', 'es'] as $locale) {
            foreach ($fields as $field) {
                $value = is_array($translations[$locale] ?? null)
                    ? ($translations[$locale][$field] ?? null)
                    : null;

                $values[$locale][$field] = is_string($value) && trim($value) !== ''
                    ? $value
                    : null;
            }
        }

        return $values;
    }
}
