<?php

declare(strict_types=1);

namespace Modules\Quote\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Modules\Catalog\Models\Product;
use Modules\Quote\Models\Coupon;
use Modules\Quote\Models\Quote;
use Tests\TestCase;

class CouponTest extends TestCase
{
    use RefreshDatabase;

    private function product(): Product
    {
        return Product::query()->create([
            'name' => 'Resistência cartucho',
            'slug' => 'resistencia-cartucho',
            'price' => '100.00',
        ]);
    }

    public function test_a_valid_coupon_discounts_the_cart_and_the_order(): void
    {
        Coupon::query()->create(['code' => 'DEZ', 'percent' => 10]);
        $product = $this->product();

        $this->post(route('quote.item.store'), [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $this->post(route('quote.coupon.store'), ['code' => 'dez'])
            ->assertRedirect();

        $this->get(route('quote.cart'))
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->where('subtotal', 200)
                ->where('discount', 20)
                ->where('coupon.code', 'DEZ')
            );

        $this->post(route('quote.store'), [
            'name' => 'João',
            'email' => 'joao@example.com',
            'website' => '',
        ])->assertRedirect(route('quote.cart'));

        $quote = Quote::query()->firstOrFail();

        $this->assertSame('DEZ', $quote->coupon_code);
        $this->assertSame('20.00', $quote->discount);
    }

    public function test_an_invalid_coupon_is_rejected(): void
    {
        $this->post(route('quote.coupon.store'), ['code' => 'NAOEXISTE'])
            ->assertSessionHasErrors('coupon');
    }

    public function test_an_admin_manages_coupons(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)
            ->post(route('quote.coupon.store.admin'), [
                'code' => 'bemvindo10',
                'percent' => 10,
            ])
            ->assertRedirect();

        $coupon = Coupon::query()->firstOrFail();
        $this->assertSame('BEMVINDO10', $coupon->code);

        $this->actingAs($admin)
            ->patch(route('quote.coupon.toggle', $coupon))
            ->assertRedirect();
        $this->assertFalse($coupon->fresh()->active);

        $this->actingAs($admin)
            ->delete(route('quote.coupon.destroy.admin', $coupon))
            ->assertRedirect();
        $this->assertSame(0, Coupon::query()->count());
    }
}
