<?php

declare(strict_types=1);

namespace Modules\Page\Data;

final readonly class PageData
{
    /**
     * @param  array<int, BlockData>  $blocks
     */
    public function __construct(
        public string $title,
        public ?string $slug,
        public array $blocks,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            title: (string) $data['title'],
            slug: isset($data['slug']) && $data['slug'] !== '' ? (string) $data['slug'] : null,
            blocks: array_map(
                fn (array $block): BlockData => BlockData::fromArray($block),
                is_array($data['blocks'] ?? null) ? $data['blocks'] : [],
            ),
        );
    }

    public function withSlug(string $slug): self
    {
        return new self($this->title, $slug, $this->blocks);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function blocksAsArray(): array
    {
        return array_map(
            fn (BlockData $block): array => $block->toArray(),
            $this->blocks,
        );
    }
}
