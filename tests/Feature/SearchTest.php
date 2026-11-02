<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Modules\Blog\Models\Post;
use Modules\Catalog\Models\Product;
use Modules\Page\Enums\PageStatus;
use Modules\Page\Models\Page;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_finds_products_posts_and_pages(): void
    {
        Product::query()->create([
            'name' => 'Resistência cartucho',
            'slug' => 'resistencia-cartucho',
            'sku' => 'RC-1',
            'price' => '99.90',
        ]);

        Post::query()->create([
            'title' => 'Como especificar uma resistência',
            'slug' => 'como-especificar',
            'body' => 'Guia técnico.',
            'published_at' => now()->subDay(),
        ]);

        Page::query()->create([
            'title' => 'Resistências sob medida',
            'slug' => 'sob-medida',
            'blocks' => [],
            'status' => PageStatus::Published,
        ]);

        $this->get('/busca?q=resistencia')
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->component('Search', false)
                ->where('term', 'resistencia')
                ->has('products', 1)
                ->has('posts', 1)
                ->has('pages', 1)
            );
    }

    public function test_an_empty_query_returns_no_results(): void
    {
        Product::query()->create(['name' => 'Qualquer', 'slug' => 'qualquer']);

        $this->get('/busca')
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->component('Search', false)
                ->where('term', '')
                ->has('products', 0)
                ->has('posts', 0)
                ->has('pages', 0)
            );
    }
}
