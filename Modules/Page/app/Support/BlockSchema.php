<?php

declare(strict_types=1);

namespace Modules\Page\Support;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\Page\Enums\BlockType;

/**
 * Single source of truth for the block payload stored in `pages.blocks`.
 * TypeScript mirrors these shapes in Modules/Page/resources/assets/js/types.ts.
 */
final class BlockSchema
{
    /**
     * Field rules per block type; `grid` is the only type with nested items.
     *
     * @var array<string, array<string, array<int, string>>>
     */
    private const RULES = [
        'hero' => [
            'title' => ['required', 'string', 'max:120'],
            'subtitle' => ['nullable', 'string', 'max:240'],
            'button_label' => ['nullable', 'string', 'max:40'],
            'button_href' => ['nullable', 'string', 'max:255'],
        ],
        'text' => [
            'body' => ['required', 'string', 'max:5000'],
        ],
        'image' => [
            'src' => ['required', 'string', 'max:2048'],
            'alt' => ['nullable', 'string', 'max:200'],
            'caption' => ['nullable', 'string', 'max:200'],
            'media_id' => ['nullable', 'integer', 'exists:media,id'],
        ],
        'cta' => [
            'title' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:300'],
            'button_label' => ['required', 'string', 'max:40'],
            'button_href' => ['nullable', 'string', 'max:255'],
        ],
        'grid' => [],
    ];

    /**
     * @param  array<int, mixed>  $blocks
     * @return array<int, array<string, mixed>>
     *
     * @throws ValidationException
     */
    public static function validate(array $blocks): array
    {
        $rules = ['blocks' => ['array', 'max:100']];

        foreach ($blocks as $index => $block) {
            $type = is_array($block) ? ($block['type'] ?? null) : null;

            if (! is_string($type) || BlockType::tryFrom($type) === null) {
                throw ValidationException::withMessages([
                    "blocks.{$index}.type" => "O bloco #{$index} tem um tipo inválido.",
                ]);
            }

            $rules["blocks.{$index}.id"] = ['required', 'string', 'max:40'];
            $rules["blocks.{$index}.type"] = ['required', 'in:'.implode(',', BlockType::values())];
            $rules["blocks.{$index}.hidden"] = ['boolean'];

            foreach (self::RULES[$type] as $field => $fieldRules) {
                $rules["blocks.{$index}.{$field}"] = $fieldRules;
            }

            if ($type === BlockType::Grid->value) {
                $rules["blocks.{$index}.items"] = ['array', 'min:1', 'max:6'];
                $rules["blocks.{$index}.items.*.title"] = ['required', 'string', 'max:120'];
                $rules["blocks.{$index}.items.*.description"] = ['nullable', 'string', 'max:300'];
            }
        }

        $validated = Validator::make(['blocks' => $blocks], $rules)->validate();

        return self::normalize($validated['blocks']);
    }

    /**
     * Keep only known keys, with stable defaults, so the stored JSON never drifts.
     *
     * @param  array<int, array<string, mixed>>  $blocks
     * @return array<int, array<string, mixed>>
     */
    private static function normalize(array $blocks): array
    {
        return array_map(function (array $block): array {
            $type = (string) $block['type'];
            $normalized = [
                'id' => (string) $block['id'],
                'type' => $type,
            ];

            if (! empty($block['hidden'])) {
                $normalized['hidden'] = true;
            }

            foreach (array_keys(self::RULES[$type]) as $field) {
                $normalized[$field] = $block[$field] ?? null;
            }

            if ($type === BlockType::Grid->value) {
                $normalized['items'] = array_values(array_map(fn (array $item): array => [
                    'title' => (string) $item['title'],
                    'description' => $item['description'] ?? '',
                ], $block['items']));
            }

            return $normalized;
        }, $blocks);
    }
}
