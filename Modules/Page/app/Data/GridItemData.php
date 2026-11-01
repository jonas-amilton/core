<?php

declare(strict_types=1);

namespace Modules\Page\Data;

final readonly class GridItemData
{
    public function __construct(
        public string $title,
        public string $description = '',
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            title: (string) ($data['title'] ?? ''),
            description: (string) ($data['description'] ?? ''),
        );
    }

    /**
     * @return array{title: string, description: string}
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
        ];
    }
}
