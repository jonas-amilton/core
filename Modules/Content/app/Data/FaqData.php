<?php

declare(strict_types=1);

namespace Modules\Content\Data;

final readonly class FaqData
{
    /**
     * @param  array<string, array<string, string>>  $translations
     */
    public function __construct(
        public string $question,
        public string $answer,
        public int $sortOrder,
        public array $translations = [],
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            question: (string) $data['question'],
            answer: (string) $data['answer'],
            sortOrder: (int) ($data['sort_order'] ?? 0),
            translations: is_array($data['translations'] ?? null) ? $data['translations'] : [],
        );
    }
}
