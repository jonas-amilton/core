<?php

declare(strict_types=1);

namespace Modules\Quote\Tests\Feature;

use App\Mail\OrderMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Inertia\Testing\AssertableInertia as Assert;
use Modules\Catalog\Models\Product;
use Modules\Quote\Models\Quote;
use Modules\Settings\Models\Setting;
use Tests\TestCase;

class QuoteTest extends TestCase
{
    use RefreshDatabase;

    private function product(): Product
    {
        return Product::query()->create([
            'name' => 'Resistência Cartucho',
            'slug' => 'resistencia-cartucho',
            'sku' => 'C-AB',
            'price' => '189.90',
        ]);
    }

    public function test_items_are_added_to_the_session_cart(): void
    {
        $product = $this->product();

        $this->post(route('quote.item.store'), [
            'product_id' => $product->id,
            'quantity' => 2,
        ])->assertRedirect();

        $this->get(route('quote.cart'))
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->component('Site::Cart', false)
                ->has('items', 1)
                ->where('items.0.name', 'Resistência Cartucho')
                ->where('items.0.quantity', 2)
                ->where('items.0.price', '189.90')
            );
    }

    public function test_the_quantity_can_be_changed_in_the_cart(): void
    {
        $product = $this->product();

        $this->post(route('quote.item.store'), [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $this->patch(route('quote.item.update', $product->id), [
            'quantity' => 4,
        ])->assertRedirect();

        $this->get(route('quote.cart'))
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->where('items.0.quantity', 4)
            );
    }

    public function test_stock_is_decremented_when_the_order_is_placed(): void
    {
        $product = $this->product();
        $product->update(['stock' => 5]);

        $this->post(route('quote.item.store'), [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $this->post(route('quote.store'), [
            'name' => 'João',
            'email' => 'joao@example.com',
            'website' => '',
        ])->assertRedirect(route('quote.cart'));

        $this->assertSame(3, $product->fresh()->stock);
    }

    public function test_a_cart_above_stock_cannot_be_submitted(): void
    {
        $product = $this->product();
        $product->update(['stock' => 5]);

        $this->post(route('quote.item.store'), [
            'product_id' => $product->id,
            'quantity' => 3,
        ]);

        $product->update(['stock' => 1]);

        $this->post(route('quote.store'), [
            'name' => 'João',
            'email' => 'joao@example.com',
            'website' => '',
        ])->assertSessionHasErrors('cart');

        $this->assertSame(1, $product->fresh()->stock);
        $this->assertSame(0, Quote::query()->count());
    }

    public function test_the_order_notifies_the_customer_and_the_store(): void
    {
        Mail::fake();
        Setting::query()->create(['key' => 'email', 'value' => 'loja@example.com']);

        $product = $this->product();

        $this->post(route('quote.item.store'), [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $this->post(route('quote.store'), [
            'name' => 'João',
            'email' => 'joao@example.com',
            'website' => '',
        ]);

        Mail::assertSent(OrderMail::class, fn (OrderMail $mail): bool => $mail->hasTo('joao@example.com'));
        Mail::assertSent(OrderMail::class, fn (OrderMail $mail): bool => $mail->hasTo('loja@example.com') && $mail->forAdmin);
    }

    public function test_an_empty_cart_cannot_be_submitted(): void
    {
        $this->post(route('quote.store'), [
            'name' => 'João',
            'email' => 'joao@example.com',
            'website' => '',
        ])->assertSessionHasErrors('cart');

        $this->assertSame(0, Quote::query()->count());
    }

    public function test_a_quote_is_stored_with_item_snapshots_and_clears_the_cart(): void
    {
        $product = $this->product();

        $this->post(route('quote.item.store'), [
            'product_id' => $product->id,
            'quantity' => 3,
        ]);

        $this->post(route('quote.store'), [
            'name' => 'João',
            'email' => 'JOAO@example.com',
            'phone' => '(51) 99999-0000',
            'message' => 'Preciso de 3 unidades.',
            'website' => '',
        ])->assertRedirect(route('quote.cart'));

        $quote = Quote::query()->with('items')->firstOrFail();

        $this->assertSame('joao@example.com', $quote->email);
        $this->assertSame('Resistência Cartucho', $quote->items->first()->name);
        $this->assertSame(3, $quote->items->first()->quantity);

        $this->get(route('quote.cart'))
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia->has('items', 0));
    }

    public function test_a_logged_in_checkout_is_linked_to_the_account(): void
    {
        $customer = User::factory()->customer()->create();
        $product = $this->product();

        $this->post(route('quote.item.store'), [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $this->actingAs($customer)->post(route('quote.store'), [
            'name' => 'João',
            'email' => 'joao@example.com',
            'website' => '',
        ])->assertRedirect(route('quote.cart'));

        $this->assertSame($customer->id, Quote::query()->firstOrFail()->user_id);
    }

    public function test_the_honeypot_blocks_bots(): void
    {
        $this->post(route('quote.item.store'), [
            'product_id' => $this->product()->id,
        ]);

        $this->post(route('quote.store'), [
            'name' => 'Bot',
            'email' => 'bot@example.com',
            'website' => 'http://spam.example',
        ])->assertSessionHasErrors('website');

        $this->assertSame(0, Quote::query()->count());
    }

    public function test_the_admin_answers_and_deletes_quotes(): void
    {
        $this->post(route('quote.item.store'), [
            'product_id' => $this->product()->id,
        ]);
        $this->post(route('quote.store'), [
            'name' => 'João',
            'email' => 'joao@example.com',
        ]);

        $quote = Quote::query()->firstOrFail();

        $this->actingAs(User::factory()->create());

        $this->get(route('quote.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->component('Quote::Index', false)
                ->has('quotes', 1)
                ->where('quotes.0.answered', false)
                ->has('quotes.0.items', 1)
            );

        $this->patch(route('quote.update', $quote), ['answered' => true])
            ->assertRedirect();

        $this->assertNotNull($quote->refresh()->answered_at);

        $this->delete(route('quote.destroy', $quote))->assertRedirect();

        $this->assertSame(0, Quote::query()->count());
    }
}
