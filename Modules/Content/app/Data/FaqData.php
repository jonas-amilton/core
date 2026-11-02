<?php

declare(strict_types=1);

namespace Modules\Content\Data;

final readonly class FaqData
{
    public function __construct(
        public string $question,
        public string $answer,
        public int $sortOrder,
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
        );
    }
}
