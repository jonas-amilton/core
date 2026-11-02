<?php

declare(strict_types=1);

namespace Modules\Content\Data;

final readonly class ClientData
{
    public function __construct(
        public string $name,
        public ?string $logo,
        public ?string $url,
        public int $sortOrder,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: (string) $data['name'],
            logo: self::string($data['logo'] ?? null),
            url: self::string($data['url'] ?? null),
            sortOrder: (int) ($data['sort_order'] ?? 0),
        );
    }

    private static function string(mixed $value): ?string
    {
        return is_string($value) && $value !== '' ? $value : null;
    }
}
