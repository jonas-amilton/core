<?php

declare(strict_types=1);

namespace Modules\Catalog\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Modules\Catalog\Models\Product;
use Tests\TestCase;

class CatalogSortTest extends TestCase
{
    use RefreshDatabase;

    private function seedProducts(): void
    {
        Product::query()->create(['name' => 'Caro', 'slug' => 'caro', 'price' => '900.00', 'position' => 0]);
        Product::query()->create(['name' => 'Barato', 'slug' => 'barato', 'price' => '100.00', 'position' => 1]);
    }

    public function test_products_can_be_sorted_by_price(): void
    {
        $this->seedProducts();

        $this->get('/produtos?sort=price_asc')
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->where('products.data.0.name', 'Barato')
                ->where('products.data.1.name', 'Caro')
            );

        $this->get('/produtos?sort=price_desc')
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->where('products.data.0.name', 'Caro')
            );
    }

    public function test_products_can_be_filtered_by_price_range(): void
    {
        $this->seedProducts();

        $this->get('/produtos?min=200&max=1000')
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->has('products.data', 1)
                ->where('products.data.0.name', 'Caro')
            );
    }
}
