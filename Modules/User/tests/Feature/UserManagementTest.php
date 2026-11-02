<?php

declare(strict_types=1);

namespace Modules\User\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_from_every_user_route(): void
    {
        $user = User::factory()->create();

        $this->get(route('user.index'))->assertRedirect(route('login'));
        $this->get(route('user.create'))->assertRedirect(route('login'));
        $this->get(route('user.edit', $user))->assertRedirect(route('login'));
        $this->delete(route('user.destroy', $user))->assertRedirect(route('login'));
    }

    public function test_the_index_lists_users(): void
    {
        $actor = User::factory()->create(['name' => 'Ana']);
        User::factory()->create(['name' => 'Bruno']);

        $this->actingAs($actor)
            ->get(route('user.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->component('User::Index', false)
                ->has('users', 2)
                ->where('users.0.name', 'Ana')
            );
    }

    public function test_a_user_can_be_created_with_a_hashed_password(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('user.store'), [
            'name' => 'Novo Admin',
            'email' => 'novo@example.com',
            'role' => 'admin',
            'password' => 'senha-forte-123',
            'password_confirmation' => 'senha-forte-123',
        ])->assertRedirect(route('user.index'));

        $user = User::query()->where('email', 'novo@example.com')->firstOrFail();

        $this->assertSame('Novo Admin', $user->name);
        $this->assertTrue(Hash::check('senha-forte-123', $user->password));
    }

    public function test_duplicated_emails_are_rejected(): void
    {
        $this->actingAs(User::factory()->create(['email' => 'existente@example.com']));

        $this->post(route('user.store'), [
            'name' => 'Duplicado',
            'email' => 'existente@example.com',
            'role' => 'admin',
            'password' => 'senha-forte-123',
            'password_confirmation' => 'senha-forte-123',
        ])->assertSessionHasErrors('email');
    }

    public function test_a_user_can_be_updated_without_changing_the_password(): void
    {
        $this->actingAs(User::factory()->create());
        $user = User::factory()->create([
            'name' => 'Antigo',
            'email' => 'antigo@example.com',
        ]);
        $originalPassword = $user->password;

        $this->patch(route('user.update', $user), [
            'name' => 'Atualizado',
            'email' => 'atualizado@example.com',
            'role' => 'admin',
        ])->assertRedirect();

        $user->refresh();

        $this->assertSame('Atualizado', $user->name);
        $this->assertSame('atualizado@example.com', $user->email);
        $this->assertSame($originalPassword, $user->password);
    }

    public function test_a_user_password_can_be_reset_from_the_panel(): void
    {
        $this->actingAs(User::factory()->create());
        $user = User::factory()->create();

        $this->patch(route('user.update', $user), [
            'name' => $user->name,
            'email' => $user->email,
            'role' => 'admin',
            'password' => 'outra-senha-456',
            'password_confirmation' => 'outra-senha-456',
        ])->assertRedirect();

        $this->assertTrue(Hash::check('outra-senha-456', $user->refresh()->password));
    }

    public function test_a_user_cannot_delete_their_own_account(): void
    {
        $actor = User::factory()->create();
        User::factory()->create();

        $this->actingAs($actor)
            ->delete(route('user.destroy', $actor))
            ->assertRedirect()
            ->assertSessionHasErrors('user');

        $this->assertDatabaseHas('users', ['id' => $actor->id]);
    }

    public function test_another_user_can_be_deleted(): void
    {
        $actor = User::factory()->create();
        $other = User::factory()->create();

        $this->actingAs($actor)
            ->delete(route('user.destroy', $other))
            ->assertRedirect(route('user.index'));

        $this->assertDatabaseMissing('users', ['id' => $other->id]);
        $this->assertDatabaseHas('users', ['id' => $actor->id]);
    }
}
