<?php

declare(strict_types=1);

namespace Modules\Contact\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Modules\Contact\Models\ContactMessage;
use Modules\Contact\Models\Subscriber;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_message_is_stored_and_validated(): void
    {
        $this->from('/contato')
            ->post(route('contact.store'), [
                'name' => 'João',
                'email' => 'joao@example.com',
                'phone' => '(54) 99999-0000',
                'message' => 'Preciso de ajuda.',
                'website' => '',
            ])
            ->assertRedirect('/contato');

        $this->assertSame('joao@example.com', ContactMessage::query()->firstOrFail()->email);

        $this->post(route('contact.store'), [
            'name' => 'João',
            'email' => 'invalido',
            'message' => '',
        ])->assertSessionHasErrors(['email', 'message']);
    }

    public function test_the_honeypot_blocks_bots(): void
    {
        $this->post(route('contact.store'), [
            'name' => 'Bot',
            'email' => 'bot@example.com',
            'message' => 'spam',
            'website' => 'http://spam.example',
        ])->assertSessionHasErrors('website');

        $this->assertSame(0, ContactMessage::query()->count());
    }

    public function test_subscriptions_are_unique(): void
    {
        $this->post(route('newsletter.store'), ['email' => 'ana@example.com']);
        $this->post(route('newsletter.store'), ['email' => 'ANA@example.com']);

        $this->assertSame(1, Subscriber::query()->count());
        $this->assertSame('ana@example.com', Subscriber::query()->firstOrFail()->email);

        $this->post(route('newsletter.store'), ['email' => 'nao-e-email'])
            ->assertSessionHasErrors('email');
    }

    public function test_the_admin_sees_messages_and_can_export_subscribers(): void
    {
        $this->post(route('newsletter.store'), ['email' => 'ana@example.com']);
        $this->post(route('contact.store'), [
            'name' => 'João',
            'email' => 'joao@example.com',
            'message' => 'Oi',
        ]);

        $this->actingAs(User::factory()->create());

        $this->get(route('contact.message.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->component('Contact::Index', false)
                ->has('messages', 1)
                ->has('subscribers', 1)
            );

        $this->get(route('contact.subscriber.export'))
            ->assertOk()
            ->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }
}
