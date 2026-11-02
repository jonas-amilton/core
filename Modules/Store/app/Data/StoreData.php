<?php

declare(strict_types=1);

namespace Modules\Store\Data;

use Modules\Store\Enums\StoreKind;

final readonly class StoreData
{
    public function __construct(
        public string $name,
        public StoreKind $kind,
        public ?string $address,
        public ?string $phone,
        public ?string $url,
        public int $position,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: (string) $data['name'],
            kind: StoreKind::tryFrom((string) ($data['kind'] ?? '')) ?? StoreKind::Physical,
            address: self::string($data['address'] ?? null),
            phone: self::string($data['phone'] ?? null),
            url: self::string($data['url'] ?? null),
            position: (int) ($data['position'] ?? 0),
        );
    }

    private static function string(mixed $value): ?string
    {
        return is_string($value) && $value !== '' ? $value : null;
    }
}
