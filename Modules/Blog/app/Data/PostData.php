<?php

declare(strict_types=1);

namespace Modules\Blog\Data;

final readonly class PostData
{
    /**
     * @param  array<string, array<string, string>>  $translations
     */
    public function __construct(
        public string $title,
        public ?string $slug,
        public ?int $categoryId,
        public ?string $excerpt,
        public string $body,
        public ?string $cover,
        public bool $published,
        public array $translations = [],
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            title: (string) $data['title'],
            slug: isset($data['slug']) && $data['slug'] !== '' ? (string) $data['slug'] : null,
            categoryId: isset($data['category_id']) ? (int) $data['category_id'] : null,
            excerpt: self::string($data['excerpt'] ?? null),
            body: (string) $data['body'],
            cover: self::string($data['cover'] ?? null),
            published: (bool) ($data['published'] ?? false),
            translations: is_array($data['translations'] ?? null) ? $data['translations'] : [],
        );
    }

    private static function string(mixed $value): ?string
    {
        return is_string($value) && $value !== '' ? $value : null;
    }
}
