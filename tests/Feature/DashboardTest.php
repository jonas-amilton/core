<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Modules\Media\Models\Media;
use Modules\Page\Enums\PageStatus;
use Modules\Page\Models\Page;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_login_page()
    {
        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_users_can_visit_the_dashboard()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('dashboard'));
        $response->assertOk();
    }

    public function test_the_dashboard_shows_real_counts()
    {
        $this->actingAs(User::factory()->create());

        Page::create([
            'title' => 'Publicada',
            'slug' => 'publicada',
            'blocks' => [],
            'status' => PageStatus::Published,
            'published_at' => now()->subMinute(),
        ]);
        Page::create([
            'title' => 'Rascunho',
            'slug' => 'rascunho',
            'blocks' => [],
            'status' => PageStatus::Draft,
        ]);

        Media::create([
            'disk' => 'public',
            'path' => 'media/teste.jpg',
            'original_name' => 'teste.jpg',
            'mime' => 'image/jpeg',
            'size' => 1024,
        ]);

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->component('Dashboard', false)
                ->where('pagesCount', 1)
                ->where('mediaCount', 1)
                ->where('adminsCount', 1)
            );
    }
}
