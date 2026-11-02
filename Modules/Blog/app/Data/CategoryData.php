<?php

declare(strict_types=1);

namespace Modules\Blog\Data;

final readonly class CategoryData
{
    /**
     * @param  array<string, array<string, string>>  $translations
     */
    public function __construct(
        public string $name,
        public ?string $slug,
        public array $translations = [],
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: (string) $data['name'],
            slug: isset($data['slug']) && $data['slug'] !== '' ? (string) $data['slug'] : null,
            translations: is_array($data['translations'] ?? null) ? $data['translations'] : [],
        );
    }
}
