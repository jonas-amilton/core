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
}
