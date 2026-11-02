<?php

declare(strict_types=1);

namespace Modules\Page\Data;

use Modules\Page\Enums\BlockType;

/**
 * A single page section. One readonly shape covers the five block types; the
 * per-type requirements live in Support/BlockSchema.
 */
final readonly class BlockData
{
    /**
     * @param  array<int, GridItemData>  $items
     */
    public function __construct(
        public string $id,
        public BlockType $type,
        public bool $hidden = false,
        public ?string $title = null,
        public ?string $subtitle = null,
        public ?string $body = null,
        public ?string $src = null,
        public ?string $alt = null,
        public ?string $caption = null,
        public ?int $mediaId = null,
        public ?string $description = null,
        public ?string $buttonLabel = null,
        public ?string $buttonHref = null,
        public ?int $limit = null,
        public array $items = [],
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        $items = array_map(
            fn (array $item): GridItemData => GridItemData::fromArray($item),
            is_array($data['items'] ?? null) ? $data['items'] : [],
        );

        return new self(
            id: (string) $data['id'],
            type: BlockType::from((string) $data['type']),
            hidden: (bool) ($data['hidden'] ?? false),
            title: self::string($data['title'] ?? null),
            subtitle: self::string($data['subtitle'] ?? null),
            body: self::string($data['body'] ?? null),
            src: self::string($data['src'] ?? null),
            alt: self::string($data['alt'] ?? null),
            caption: self::string($data['caption'] ?? null),
            mediaId: isset($data['media_id']) ? (int) $data['media_id'] : null,
            description: self::string($data['description'] ?? null),
            buttonLabel: self::string($data['button_label'] ?? null),
            buttonHref: self::string($data['button_href'] ?? null),
            limit: isset($data['limit']) ? (int) $data['limit'] : null,
            items: array_values($items),
        );
    }

    /**
     * Snapshot of the block for rendering (admin editor, preview and public site).
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = [
            'id' => $this->id,
            'type' => $this->type->value,
        ];

        if ($this->hidden) {
            $data['hidden'] = true;
        }

        foreach ([
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'body' => $this->body,
            'src' => $this->src,
            'alt' => $this->alt,
            'caption' => $this->caption,
            'description' => $this->description,
            'button_label' => $this->buttonLabel,
            'button_href' => $this->buttonHref,
        ] as $key => $value) {
            if ($value !== null) {
                $data[$key] = $value;
            }
        }

        if ($this->mediaId !== null) {
            $data['media_id'] = $this->mediaId;
        }

        if ($this->limit !== null) {
            $data['limit'] = $this->limit;
        }

        if ($this->items !== []) {
            $data['items'] = array_map(
                fn (GridItemData $item): array => $item->toArray(),
                $this->items,
            );
        }

        return $data;
    }

    private static function string(mixed $value): ?string
    {
        return is_string($value) && $value !== '' ? $value : null;
    }
}
