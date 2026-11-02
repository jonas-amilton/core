<?php

declare(strict_types=1);

namespace Modules\Page\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Page\Data\PageData;
use Modules\Page\Support\BlockSchema;

abstract class PageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:200'],
            'slug' => ['nullable', 'string', 'max:200', 'alpha_dash'],
            'blocks' => ['present', 'array'],
            'icon' => ['nullable', 'string', 'max:2048'],
            'is_service' => ['boolean'],
            'translations' => ['nullable', 'array'],
            'translations.*.title' => ['nullable', 'string', 'max:200'],
            'translations.*.blocks' => ['nullable', 'array'],
        ];
    }

    /**
     * Keeps only languages that actually carry a translated title or blocks.
     *
     * @return array<string, array{title: string|null, blocks: array<int, array<string, mixed>>}>
     */
    private function translations(): array
    {
        $translations = [];

        foreach (['en', 'es'] as $locale) {
            $input = $this->input("translations.{$locale}");

            if (! is_array($input)) {
                continue;
            }

            $title = trim((string) ($input['title'] ?? ''));
            $blocks = BlockSchema::validate(
                is_array($input['blocks'] ?? null) ? $input['blocks'] : [],
            );

            if ($title === '' && $blocks === []) {
                continue;
            }

            $translations[$locale] = [
                'title' => $title !== '' ? $title : null,
                'blocks' => $blocks,
            ];
        }

        return $translations;
    }

    public function pageData(): PageData
    {
        /** @var array<int, array<string, mixed>> $blocks */
        $blocks = $this->input('blocks', []);

        return PageData::fromArray([
            'title' => (string) $this->input('title'),
            'slug' => $this->input('slug'),
            'blocks' => BlockSchema::validate($blocks),
            'icon' => $this->input('icon'),
            'is_service' => $this->boolean('is_service'),
            'translations' => $this->translations(),
        ]);
    }
}
