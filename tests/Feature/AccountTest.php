<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Modules\Quote\Models\Quote;
use Modules\Quote\Models\QuoteItem;
use Tests\TestCase;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    private function order(User $customer, string $name): Quote
    {
        $quote = Quote::query()->create([
            'user_id' => $customer->id,
            'name' => $name,
            'email' => $customer->email,
        ]);

        QuoteItem::query()->create([
            'quote_id' => $quote->id,
            'name' => 'Resistência cartucho',
            'quantity' => 2,
            'unit_price' => '100.00',
        ]);

        return $quote;
    }

    public function test_guests_are_sent_to_the_login_screen(): void
    {
        $this->get('/minha-conta')->assertRedirect(route('login'));
    }

    public function test_a_customer_only_sees_their_own_orders(): void
    {
        $customer = User::factory()->customer()->create(['email' => 'cliente@example.com']);
        $other = User::factory()->customer()->create(['email' => 'outro@example.com']);

        $own = $this->order($customer, 'Cliente');
        $this->order($other, 'Outro');

        $this->actingAs($customer)
            ->get('/minha-conta')
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->component('Site::Account', false)
                ->has('orders', 1)
                ->where('orders.0.id', $own->id)
                ->where('orders.0.total', 200)
            );
    }

    public function test_an_account_does_not_see_guest_orders_with_its_email(): void
    {
        $guestOrder = Quote::query()->create([
            'name' => 'Visitante',
            'email' => 'cliente@example.com',
        ]);

        $customer = User::factory()->customer()->create(['email' => 'cliente@example.com']);

        $this->actingAs($customer)
            ->get('/minha-conta')
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->component('Site::Account', false)
                ->has('orders', 0)
            );

        $this->assertNull($guestOrder->fresh()->user_id);
    }
}
