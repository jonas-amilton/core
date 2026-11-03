<?php

declare(strict_types=1);

namespace Modules\Settings\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Modules\Settings\Models\Setting;
use Modules\Settings\Services\SettingsService;
use Tests\TestCase;

class SettingsManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_open_settings(): void
    {
        $this->get(route('settings.index'))->assertRedirect(route('login'));
    }

    public function test_the_admin_form_shows_defaults(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get(route('settings.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->component('Settings::Index', false)
                ->where('values.site_name', 'CoreCMS')
                ->has('fields')
            );
    }

    public function test_the_cookie_policy_page_is_public(): void
    {
        $this->get(route('settings.cookie-policy'))
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->component('Site::CookiePolicy', false)
                ->has('navigation')
                ->where('cookieText', fn (string $text): bool => $text !== '')
            );
    }

    public function test_settings_are_saved_and_unknown_keys_are_dropped(): void
    {
        $this->actingAs(User::factory()->create());

        $this->put(route('settings.update'), [
            'site_name' => 'Cruzeiro',
            'email' => 'contato@example.com',
            'unknown' => 'ignorado',
        ])->assertRedirect(route('settings.index'));

        $values = app(SettingsService::class)->all();

        $this->assertSame('Cruzeiro', $values['site_name']);
        $this->assertSame('contato@example.com', $values['email']);
        $this->assertArrayNotHasKey('unknown', $values);
        $this->assertSame(2, Setting::query()->count());
    }
}
