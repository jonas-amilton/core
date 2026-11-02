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

    private function order(string $email, string $name): Quote
    {
        $quote = Quote::query()->create([
            'name' => $name,
            'email' => $email,
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
        $own = $this->order('cliente@example.com', 'Cliente');
        $this->order('outro@example.com', 'Outro');

        $this->actingAs($customer)
            ->get('/minha-conta')
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->component('Account', false)
                ->has('orders', 1)
                ->where('orders.0.id', $own->id)
                ->where('orders.0.total', 200)
            );
    }
}
