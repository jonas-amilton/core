<?php

declare(strict_types=1);

namespace Modules\Catalog\Data;

final readonly class ProductData
{
    /**
     * @param  array<int, int>  $categoryIds
     * @param  array<int, int>  $mediaIds
     * @param  array<int, int>  $relatedIds
     * @param  array<string, array<string, string>>  $translations
     */
    public function __construct(
        public string $name,
        public ?string $slug,
        public ?string $sku,
        public ?string $description,
        public int $position,
        public ?float $price = null,
        public ?int $stock = null,
        public array $categoryIds = [],
        public array $mediaIds = [],
        public array $relatedIds = [],
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
            sku: self::string($data['sku'] ?? null),
            description: self::string($data['description'] ?? null),
            position: (int) ($data['position'] ?? 0),
            price: self::decimal($data['price'] ?? null),
            stock: self::integer($data['stock'] ?? null),
            categoryIds: self::integers($data['category_ids'] ?? []),
            mediaIds: self::integers($data['media_ids'] ?? []),
            relatedIds: self::integers($data['related_ids'] ?? []),
            translations: is_array($data['translations'] ?? null) ? $data['translations'] : [],
        );
    }

    private static function string(mixed $value): ?string
    {
        return is_string($value) && $value !== '' ? $value : null;
    }

    private static function decimal(mixed $value): ?float
    {
        return is_numeric($value) ? (float) $value : null;
    }

    private static function integer(mixed $value): ?int
    {
        return is_numeric($value) ? max(0, (int) $value) : null;
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
