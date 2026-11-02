<?php

declare(strict_types=1);

namespace Modules\Catalog\Data;

final readonly class CategoryData
{
    /**
     * @param  array<string, array<string, string>>  $translations
     */
    public function __construct(
        public string $name,
        public ?string $slug,
        public ?int $parentId,
        public int $position,
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
            parentId: isset($data['parent_id']) && $data['parent_id'] !== '' ? (int) $data['parent_id'] : null,
            position: (int) ($data['position'] ?? 0),
            translations: is_array($data['translations'] ?? null) ? $data['translations'] : [],
        );
    }
}
