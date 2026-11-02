<?php

declare(strict_types=1);

namespace Modules\Catalog\Data;

final readonly class ProductData
{
    /**
     * @param  array<int, int>  $categoryIds
     * @param  array<int, int>  $mediaIds
     * @param  array<int, int>  $relatedIds
     */
    public function __construct(
        public string $name,
        public ?string $slug,
        public ?string $sku,
        public ?string $description,
        public int $position,
        public array $categoryIds = [],
        public array $mediaIds = [],
        public array $relatedIds = [],
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: (string) $data['name'],
            slug: isset($data['slug']) && $data['slug'] !== '' ? (string) $data['slug'] : null,
            sku: self::string($data['sku'] ?? null),
            description: self::string($data['description'] ?? null),
            position: (int) ($data['position'] ?? 0),
            categoryIds: self::integers($data['category_ids'] ?? []),
            mediaIds: self::integers($data['media_ids'] ?? []),
            relatedIds: self::integers($data['related_ids'] ?? []),
        );
    }

    private static function string(mixed $value): ?string
    {
        return is_string($value) && $value !== '' ? $value : null;
    }

    /**
     * @return array<int, int>
     */
    private static function integers(mixed $value): array
    {
        return is_array($value)
            ? array_values(array_unique(array_map('intval', $value)))
            : [];
    }
}
