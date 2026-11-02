<?php

declare(strict_types=1);

namespace Modules\Blog\Data;

final readonly class CategoryData
{
    public function __construct(
        public string $name,
        public ?string $slug,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: (string) $data['name'],
            slug: isset($data['slug']) && $data['slug'] !== '' ? (string) $data['slug'] : null,
        );
    }
}
