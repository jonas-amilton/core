<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PanelAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_customers_cannot_open_the_panel(): void
    {
        $customer = User::factory()->customer()->create();

        $this->actingAs($customer)
            ->get(route('catalog.product.index'))
            ->assertForbidden();
    }

    public function test_editors_manage_content_but_not_users_or_settings(): void
    {
        $editor = User::factory()->editor()->create();

        $this->actingAs($editor)
            ->get(route('catalog.product.index'))
            ->assertOk();

        $this->actingAs($editor)
            ->get(route('user.index'))
            ->assertForbidden();

        $this->actingAs($editor)
            ->get(route('settings.index'))
            ->assertForbidden();
    }

    public function test_admins_can_open_every_panel_area(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)
            ->get(route('user.index'))
            ->assertOk();

        $this->actingAs($admin)
            ->get(route('settings.index'))
            ->assertOk();
    }
}
