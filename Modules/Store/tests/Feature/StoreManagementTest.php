<?php

declare(strict_types=1);

namespace Modules\Store\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Modules\Store\Models\Store;
use Tests\TestCase;

class StoreManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_manage_stores(): void
    {
        $this->get(route('stores.index'))->assertRedirect(route('login'));
        $this->post(route('stores.store'), [])->assertRedirect(route('login'));
    }

    public function test_stores_are_created_updated_and_deleted(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('stores.store'), [
            'name' => 'Matriz',
            'kind' => 'physical',
            'address' => 'Rua A, 100',
            'phone' => '(51) 3133-5291',
            'position' => 1,
        ])->assertRedirect(route('stores.index'));

        $store = Store::query()->firstOrFail();
        $this->assertSame('Matriz', $store->name);

        $this->patch(route('stores.update', $store), [
            'name' => 'Matriz Centro',
            'kind' => 'online',
            'url' => 'https://example.com',
        ])->assertRedirect();

        $this->assertSame('Matriz Centro', $store->refresh()->name);
        $this->assertSame('online', $store->refresh()->kind->value);

        $this->delete(route('stores.destroy', $store))->assertRedirect();

        $this->assertSame(0, Store::query()->count());
    }

    public function test_the_public_page_groups_physical_and_online_stores(): void
    {
        Store::query()->create([
            'name' => 'Loja física',
            'kind' => 'physical',
            'address' => 'Rua A, 100',
        ]);
        Store::query()->create([
            'name' => 'Mercado Livre',
            'kind' => 'online',
            'url' => 'https://mercadolivre.com.br',
        ]);

        $this->get(route('stores.public'))
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->component('Store::WhereToBuy', false)
                ->has('physical', 1)
                ->where('physical.0.name', 'Loja física')
                ->has('online', 1)
                ->where('online.0.name', 'Mercado Livre')
            );
    }

    public function test_required_fields_are_validated(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('stores.store'), ['name' => '', 'kind' => 'invalid'])
            ->assertSessionHasErrors(['name', 'kind']);

        $this->assertSame(0, Store::query()->count());
    }
}
