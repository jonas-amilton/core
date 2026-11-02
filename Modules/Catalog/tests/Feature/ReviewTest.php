<?php

declare(strict_types=1);

namespace Modules\Catalog\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Modules\Catalog\Models\Product;
use Modules\Catalog\Models\Review;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    private function product(): Product
    {
        return Product::query()->create([
            'name' => 'Resistência tubular',
            'slug' => 'resistencia-tubular',
            'price' => '289.00',
        ]);
    }

    public function test_a_guest_review_is_stored_pending_moderation(): void
    {
        $product = $this->product();

        $this->from(route('catalog.show', $product))
            ->post(route('catalog.review.store', $product), [
                'name' => 'João',
                'email' => 'joao@example.com',
                'rating' => 5,
                'body' => 'Excelente acabamento.',
                'website' => '',
            ])
            ->assertRedirect(route('catalog.show', $product));

        $review = Review::query()->firstOrFail();

        $this->assertFalse($review->approved);
        $this->assertSame(5, $review->rating);
    }

    public function test_the_honeypot_blocks_bots(): void
    {
        $product = $this->product();

        $this->post(route('catalog.review.store', $product), [
            'name' => 'Bot',
            'email' => 'bot@example.com',
            'rating' => 5,
            'body' => 'spam',
            'website' => 'http://spam.example',
        ])->assertSessionHasErrors('website');

        $this->assertSame(0, Review::query()->count());
    }

    public function test_only_approved_reviews_are_public(): void
    {
        $product = $this->product();

        Review::query()->create([
            'product_id' => $product->id,
            'name' => 'Aprovada',
            'email' => 'a@example.com',
            'rating' => 5,
            'body' => 'Bom.',
            'approved' => true,
        ]);

        Review::query()->create([
            'product_id' => $product->id,
            'name' => 'Pendente',
            'email' => 'b@example.com',
            'rating' => 3,
            'body' => 'Mais ou menos.',
            'approved' => false,
        ]);

        $this->get(route('catalog.show', $product))
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->has('reviews', 1)
                ->where('reviews.0.name', 'Aprovada')
            );
    }

    public function test_an_admin_can_approve_and_delete_reviews(): void
    {
        $admin = User::factory()->create();
        $review = Review::query()->create([
            'product_id' => $this->product()->id,
            'name' => 'Cliente',
            'email' => 'c@example.com',
            'rating' => 4,
            'body' => 'Bom.',
        ]);

        $this->actingAs($admin)
            ->patch(route('catalog.review.update', $review))
            ->assertRedirect();

        $this->assertTrue($review->fresh()->approved);

        $this->actingAs($admin)
            ->delete(route('catalog.review.destroy', $review))
            ->assertRedirect();

        $this->assertSame(0, Review::query()->count());
    }
}
