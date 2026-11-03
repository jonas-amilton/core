<?php

declare(strict_types=1);

namespace Modules\Page\Tests\Unit;

use Illuminate\Validation\ValidationException;
use Modules\Page\Support\BlockSchema;
use Tests\TestCase;

class BlockSchemaTest extends TestCase
{
    /**
     * @return array<string, array<int, array<string, mixed>>>
     */
    public static function validBlocks(): array
    {
        return [
            'hero' => [[
                'id' => 'h1',
                'type' => 'hero',
                'title' => 'Título',
                'subtitle' => 'Sub',
                'button_label' => 'Ir',
                'button_href' => '/x',
            ]],
            'text' => [[
                'id' => 't1',
                'type' => 'text',
                'body' => 'Parágrafo',
            ]],
            'image' => [[
                'id' => 'i1',
                'type' => 'image',
                'src' => 'https://example.com/a.png',
                'alt' => 'Descrição',
                'caption' => 'Legenda',
            ]],
            'cta' => [[
                'id' => 'c1',
                'type' => 'cta',
                'title' => 'Vamos?',
                'button_label' => 'Contato',
            ]],
            'grid' => [[
                'id' => 'g1',
                'type' => 'grid',
                'items' => [
                    ['title' => 'A', 'description' => ''],
                    ['title' => 'B'],
                ],
            ]],
        ];
    }

    public function test_each_block_type_accepts_a_minimal_payload(): void
    {
        foreach (self::validBlocks() as $name => $blocks) {
            $validated = BlockSchema::validate($blocks);

            $this->assertSame($blocks[0]['type'], $validated[0]['type'], $name);
            $this->assertSame($blocks[0]['id'], $validated[0]['id'], $name);
        }
    }

    public function test_normalization_drops_unknown_keys_and_keeps_hidden(): void
    {
        $validated = BlockSchema::validate([[
            'id' => 't1',
            'type' => 'text',
            'body' => 'Texto',
            'junk' => 'não deveria persistir',
            'hidden' => true,
        ]]);

        $this->assertArrayNotHasKey('junk', $validated[0]);
        $this->assertTrue($validated[0]['hidden']);
    }

    public function test_unknown_types_are_rejected(): void
    {
        $this->expectException(ValidationException::class);

        BlockSchema::validate([['id' => 'x', 'type' => 'video']]);
    }

    public function test_hero_requires_a_title(): void
    {
        $this->expectException(ValidationException::class);

        BlockSchema::validate([['id' => 'h1', 'type' => 'hero']]);
    }

    public function test_grid_requires_items(): void
    {
        $this->expectException(ValidationException::class);

        BlockSchema::validate([['id' => 'g1', 'type' => 'grid', 'items' => []]]);
    }

    public function test_grid_items_need_a_title(): void
    {
        try {
            BlockSchema::validate([[
                'id' => 'g1',
                'type' => 'grid',
                'items' => [['title' => '']],
            ]]);
            $this->fail('Esperava erro de validação.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('blocks.0.items.0.title', $exception->errors());
        }
    }

    public function test_long_titles_are_rejected(): void
    {
        $this->expectException(ValidationException::class);

        BlockSchema::validate([[
            'id' => 'h1',
            'type' => 'hero',
            'title' => str_repeat('a', 121),
        ]]);
    }

    public function test_executable_schemes_are_rejected_in_block_links(): void
    {
        foreach (['hero', 'cta'] as $type) {
            try {
                BlockSchema::validate([[
                    'id' => 'b1',
                    'type' => $type,
                    'title' => 'Título',
                    'button_label' => 'Ir',
                    'button_href' => 'javascript:alert(1)',
                ]]);

                $this->fail("Esperava erro de validação para o bloco {$type}.");
            } catch (ValidationException $exception) {
                $this->assertArrayHasKey('blocks.0.button_href', $exception->errors());
            }
        }
    }
}
