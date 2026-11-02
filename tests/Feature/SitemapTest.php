<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Catalog\Models\Product;
use Tests\TestCase;

class SitemapTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_sitemap_lists_products_and_static_pages(): void
    {
        Product::query()->create(['name' => 'Resistência', 'slug' => 'resistencia']);

        $response = $this->get('/sitemap.xml')->assertOk();

        $this->assertStringContainsString('application/xml', (string) $response->headers->get('Content-Type'));
        $response->assertSee('/produto/resistencia', false);
        $response->assertSee('/produtos', false);
    }
}
