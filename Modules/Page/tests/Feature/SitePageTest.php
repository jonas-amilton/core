<?php

declare(strict_types=1);

namespace Modules\Page\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Modules\Page\Enums\PageStatus;
use Modules\Page\Models\Page;
use Tests\TestCase;

class SitePageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @param  array<string, mixed>  $overrides
     */
    private function createPage(array $overrides = []): Page
    {
        return Page::create(array_merge([
            'title' => 'Sobre nós',
            'slug' => 'sobre-nos',
            'blocks' => [
                ['id' => 'b1', 'type' => 'text', 'body' => 'Conteúdo'],
            ],
            'status' => PageStatus::Published,
            'published_at' => now()->subMinute(),
        ], $overrides));
    }

    public function test_home_and_home_slug_serve_the_same_page(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->component('Page::Show', false)
                ->where('page.slug', 'home')
            );

        $this->get('/home')
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->component('Page::Show', false)
                ->where('page.slug', 'home')
            );

        $this->assertSame(1, Page::query()->where('slug', 'home')->count());
    }

    public function test_page_translations_follow_the_session_language(): void
    {
        $this->createPage([
            'translations' => [
                'en' => [
                    'title' => 'About us',
                    'blocks' => [
                        ['id' => 'b1', 'type' => 'text', 'body' => 'Content'],
                    ],
                ],
            ],
        ]);

        $this->get('/sobre-nos')
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->where('page.title', 'Sobre nós')
                ->where('page.blocks.0.body', 'Conteúdo')
            );

        $this->get('/sobre-nos?locale=en')
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->where('page.title', 'About us')
                ->where('page.blocks.0.body', 'Content')
            );
    }

    public function test_published_pages_are_publicly_accessible(): void
    {
        $this->createPage();

        $this->get('/sobre-nos')
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->component('Page::Show', false)
                ->where('page.slug', 'sobre-nos')
                ->where('page.title', 'Sobre nós')
            );
    }

    public function test_draft_pages_are_not_public(): void
    {
        $this->createPage(['status' => PageStatus::Draft, 'published_at' => null]);

        $this->get('/sobre-nos')->assertNotFound();
    }

    public function test_scheduled_pages_only_appear_after_their_date(): void
    {
        $this->createPage([
            'status' => PageStatus::Scheduled,
            'published_at' => now()->addDay(),
        ]);

        $this->get('/sobre-nos')->assertNotFound();

        $this->createPage([
            'slug' => 'ja-publicada',
            'status' => PageStatus::Scheduled,
            'published_at' => now()->subMinute(),
        ]);

        $this->get('/ja-publicada')->assertOk();
    }

    public function test_unknown_slugs_return_not_found(): void
    {
        $this->get('/nao-existe')->assertNotFound();
    }

    public function test_hidden_blocks_never_reach_the_public_payload(): void
    {
        $this->createPage([
            'blocks' => [
                ['id' => 'b1', 'type' => 'text', 'body' => 'Visível'],
                ['id' => 'b2', 'type' => 'text', 'body' => 'Oculto', 'hidden' => true],
            ],
        ]);

        $this->get('/sobre-nos')
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->component('Page::Show', false)
                ->has('page.blocks', 1)
                ->where('page.blocks.0.body', 'Visível')
            );
    }

    public function test_navigation_lists_only_published_pages_with_home_first(): void
    {
        $this->get('/')->assertOk(); // provisiona a home

        $this->createPage();
        $this->createPage(['title' => 'Contato', 'slug' => 'contato']);
        $this->createPage(['title' => 'Rascunho', 'slug' => 'rascunho', 'status' => PageStatus::Draft, 'published_at' => null]);

        $this->get('/sobre-nos')
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->component('Page::Show', false)
                ->has('navigation', 3)
                ->where('navigation.0.slug', 'home')
                ->where('navigation.1.slug', 'sobre-nos')
                ->where('navigation.2.slug', 'contato')
            );
    }

    public function test_reserved_paths_keep_their_own_routes(): void
    {
        $this->createPage(['slug' => 'admin']); // nunca deve ser servido em /admin

        $this->get('/login')->assertOk();
        $this->get('/admin')
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia->component('Welcome', false));
        $this->get('/pages')->assertRedirect(route('login'));
    }

    public function test_the_admin_landing_is_public(): void
    {
        $this->get('/admin')
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia->component('Welcome', false));
    }

    public function test_authenticated_users_are_redirected_away_from_login(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get('/login')->assertRedirect();
    }
}
