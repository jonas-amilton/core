<?php

declare(strict_types=1);

namespace Modules\Catalog\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Modules\Catalog\Models\Category;
use Modules\Catalog\Models\Product;
use Modules\Media\Models\Media;
use Tests\TestCase;

class CatalogManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function payload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Resistência Cartucho',
            'slug' => null,
            'sku' => 'C-AB',
            'description' => 'Cartucho de alta carga.',
            'position' => 0,
            'category_ids' => [],
            'media_ids' => [],
            'related_ids' => [],
        ], $overrides);
    }

    public function test_guests_cannot_manage_the_catalog(): void
    {
        $this->get(route('catalog.product.index'))->assertRedirect(route('login'));
        $this->post(route('catalog.product.store'), $this->payload())
            ->assertRedirect(route('login'));
    }

    public function test_a_product_gets_a_slug_and_appears_on_the_site(): void
    {
        $this->actingAs(User::factory()->create());

        $category = Category::query()->create([
            'name' => 'Cartuchos',
            'slug' => 'cartuchos',
        ]);

        $media = Media::query()->create([
            'disk' => 'public',
            'path' => 'media/cartucho.png',
            'original_name' => 'cartucho.png',
            'mime' => 'image/png',
            'size' => 1024,
        ]);

        $this->post(route('catalog.product.store'), $this->payload([
            'category_ids' => [$category->id],
            'media_ids' => [$media->id],
        ]))->assertRedirect();

        $product = Product::query()->firstOrFail();

        $this->assertSame('resistencia-cartucho', $product->slug);
        $this->assertTrue($product->categories->contains($category));
        $this->assertTrue($product->media->contains($media));

        $this->get(route('catalog.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->component('Catalog::Index', false)
                ->has('products.data', 1)
                ->where('products.data.0.name', 'Resistência Cartucho')
            );

        $this->get(route('catalog.show', $product))
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->component('Catalog::Show', false)
                ->where('product.name', 'Resistência Cartucho')
                ->where('product.sku', 'C-AB')
            );
    }

    public function test_a_parent_category_lists_products_of_its_children(): void
    {
        $parent = Category::query()->create([
            'name' => 'Resistências Elétricas',
            'slug' => 'resistencias-eletricas',
        ]);
        $child = Category::query()->create([
            'parent_id' => $parent->id,
            'name' => 'Cartuchos',
            'slug' => 'cartuchos',
        ]);

        $product = Product::query()->create([
            'name' => 'Cartucho',
            'slug' => 'cartucho',
        ]);
        $product->categories()->attach($child->id);

        $this->get(route('catalog.category', $parent))
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->component('Catalog::Index', false)
                ->has('products.data', 1)
            );

        $this->get(route('catalog.category', $child))
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->has('products.data', 1)
            );

        $empty = Category::query()->create([
            'name' => 'Saunas',
            'slug' => 'saunas',
        ]);

        $this->get(route('catalog.category', $empty))
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->has('products.data', 0)
            );
    }

    public function test_slugs_are_unique_for_products_and_categories(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('catalog.product.store'), $this->payload());
        $this->post(route('catalog.product.store'), $this->payload());

        $this->assertSame(
            ['resistencia-cartucho', 'resistencia-cartucho-2'],
            Product::query()->orderBy('id')->pluck('slug')->all(),
        );

        $this->post(route('catalog.category.store'), ['name' => 'Acessórios']);
        $this->post(route('catalog.category.store'), ['name' => 'Acessórios']);

        $this->assertSame(
            ['acessorios', 'acessorios-2'],
            Category::query()->orderBy('id')->pluck('slug')->all(),
        );
    }

    public function test_products_can_be_related_and_deleted(): void
    {
        $this->actingAs(User::factory()->create());

        $first = Product::query()->create(['name' => '1L', 'slug' => '1l']);
        $second = Product::query()->create(['name' => '5L', 'slug' => '5l']);

        $this->patch(route('catalog.product.update', $first), $this->payload([
            'name' => '1L',
            'slug' => '1l',
            'related_ids' => [$second->id, $first->id],
        ]))->assertRedirect();

        $this->assertSame([$second->id], $first->refresh()->related->pluck('id')->all());

        $this->get(route('catalog.show', $first))
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->has('related', 1)
                ->where('related.0.title', '5L')
            );

        $this->delete(route('catalog.product.destroy', $second))->assertRedirect();

        $this->assertSame(0, Product::query()->whereKey($second->id)->count());
    }

    public function test_admin_screens_render(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('catalog.product.store'), $this->payload());
        $product = Product::query()->firstOrFail();
        Category::query()->create(['name' => 'Cartuchos', 'slug' => 'cartuchos']);

        $this->get(route('catalog.product.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->component('Catalog::Products', false)
                ->has('products.data', 1)
            );

        $this->get(route('catalog.product.create'))
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->component('Catalog::Editor', false)
                ->where('product', null)
            );

        $this->get(route('catalog.product.edit', $product))
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->component('Catalog::Editor', false)
                ->where('product.slug', 'resistencia-cartucho')
            );

        $this->get(route('catalog.category.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->component('Catalog::Categories', false)
                ->has('categories', 1)
            );
    }
}
