<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Modules\Catalog\Models\Product;
use Tests\TestCase;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    private function product(): Product
    {
        return Product::query()->create([
            'name' => 'Resistência cartucho',
            'slug' => 'resistencia-cartucho',
            'price' => '189.90',
        ]);
    }

    public function test_guests_are_sent_to_login(): void
    {
        $this->post(route('favorite.toggle', $this->product()))
            ->assertRedirect(route('login'));
    }

    public function test_toggling_saves_and_removes_the_product(): void
    {
        $customer = User::factory()->customer()->create();
        $product = $this->product();

        $this->actingAs($customer)
            ->post(route('favorite.toggle', $product))
            ->assertRedirect();

        $this->assertTrue($customer->favorites()->whereKey($product->id)->exists());

        $this->actingAs($customer)
            ->post(route('favorite.toggle', $product))
            ->assertRedirect();

        $this->assertFalse($customer->favorites()->whereKey($product->id)->exists());
    }

    public function test_the_account_lists_the_favorites(): void
    {
        $customer = User::factory()->customer()->create();
        $product = $this->product();
        $customer->favorites()->attach($product->id);

        $this->actingAs($customer)
            ->get('/minha-conta')
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->has('favorites', 1)
                ->where('favorites.0.name', 'Resistência cartucho')
            );
    }
}
