<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthThrottleTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_is_rate_limited(): void
    {
        for ($attempt = 0; $attempt < 6; $attempt++) {
            $this->post(route('register.store'), [
                'name' => 'Spam '.$attempt,
                'email' => "spam{$attempt}@example.com",
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);

            $this->post(route('logout'));
        }

        $this->post(route('register.store'), [
            'name' => 'Spam 7',
            'email' => 'spam7@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertStatus(429);
    }

    public function test_password_reset_requests_are_rate_limited(): void
    {
        for ($attempt = 0; $attempt < 6; $attempt++) {
            $this->post(route('password.email'), ['email' => 'nobody@example.com']);
        }

        $this->post(route('password.email'), ['email' => 'nobody@example.com'])
            ->assertStatus(429);
    }

    public function test_password_confirmation_attempts_are_rate_limited(): void
    {
        $user = User::factory()->create();

        for ($attempt = 0; $attempt < 6; $attempt++) {
            $this->actingAs($user)
                ->post(route('password.confirm.store'), ['password' => 'wrong-password']);
        }

        $this->actingAs($user)
            ->post(route('password.confirm.store'), ['password' => 'wrong-password'])
            ->assertStatus(429);
    }
}
