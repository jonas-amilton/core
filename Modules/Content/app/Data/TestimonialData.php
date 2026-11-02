<?php

declare(strict_types=1);

namespace Modules\Content\Data;

final readonly class TestimonialData
{
    public function __construct(
        public string $author,
        public ?string $role,
        public string $quote,
        public ?string $avatar,
        public int $sortOrder,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            author: (string) $data['author'],
            role: self::string($data['role'] ?? null),
            quote: (string) $data['quote'],
            avatar: self::string($data['avatar'] ?? null),
            sortOrder: (int) ($data['sort_order'] ?? 0),
        );
    }

    private static function string(mixed $value): ?string
    {
        return is_string($value) && $value !== '' ? $value : null;
    }
}
