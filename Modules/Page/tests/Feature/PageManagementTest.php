<?php

declare(strict_types=1);

namespace Modules\Page\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Modules\Page\Enums\PageStatus;
use Modules\Page\Models\Page;
use Tests\TestCase;

class PageManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function payload(array $overrides = []): array
    {
        return array_merge([
            'title' => 'Página inicial',
            'slug' => null,
            'blocks' => [
                [
                    'id' => 'blk-1',
                    'type' => 'hero',
                    'title' => 'Bem-vindo',
                    'subtitle' => 'Subtítulo',
                    'button_label' => 'Começar',
                    'button_href' => '/contato',
                ],
                [
                    'id' => 'blk-2',
                    'type' => 'grid',
                    'items' => [
                        ['title' => 'Um', 'description' => ''],
                        ['title' => 'Dois', 'description' => ''],
                    ],
                ],
            ],
        ], $overrides);
    }

    /**
     * @param  array<string, mixed>  $overrides
     */
    private function existingPage(array $overrides = []): Page
    {
        return Page::create(array_merge([
            'title' => 'Existente',
            'slug' => 'existente',
            'blocks' => [['id' => 'b1', 'type' => 'text', 'body' => 'oi']],
            'status' => PageStatus::Draft,
        ], $overrides));
    }

    public function test_guests_are_redirected_from_every_admin_route(): void
    {
        $page = $this->existingPage();

        $routes = [
            route('page.index'),
            route('page.create'),
            route('page.edit', $page),
            route('page.preview', $page),
        ];

        foreach ($routes as $url) {
            $this->get($url)->assertRedirect(route('login'));
        }

        $this->post(route('page.store'), $this->payload())->assertRedirect(route('login'));
        $this->patch(route('page.update', $page), $this->payload())->assertRedirect(route('login'));
        $this->delete(route('page.destroy', $page))->assertRedirect(route('login'));
        $this->post(route('page.publish', $page))->assertRedirect(route('login'));
        $this->post(route('page.unpublish', $page))->assertRedirect(route('login'));
    }

    public function test_the_index_provisions_and_lists_pages(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get(route('page.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->component('Page::Index', false)
                ->has('pages', 2)
                ->where('pages.0.is_home', true)
            );
    }

    public function test_a_page_can_be_created_with_blocks(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('page.store'), $this->payload())
            ->assertRedirect();

        $page = Page::query()->where('slug', 'pagina-inicial')->firstOrFail();

        $this->assertSame(PageStatus::Draft, $page->status);
        $this->assertCount(2, $page->blocks);
        $this->assertSame('hero', $page->blocks[0]['type']);
        $this->assertSame('blk-1', $page->blocks[0]['id']);
        $this->assertSame('Um', $page->blocks[1]['items'][0]['title']);
        $this->assertArrayNotHasKey('junk', $page->blocks[0]);
    }

    public function test_slugs_are_derived_from_the_title_and_unique(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('page.store'), $this->payload(['title' => 'Sobre nós']));
        $this->post(route('page.store'), $this->payload(['title' => 'Sobre nós']));

        $this->assertSame(
            ['sobre-nos', 'sobre-nos-2'],
            Page::query()->where('slug', 'like', 'sobre-nos%')->orderBy('id')->pluck('slug')->all(),
        );
    }

    public function test_reserved_slugs_are_rejected(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('page.store'), $this->payload(['title' => 'Admin']))
            ->assertSessionHasErrors('title');

        $this->post(route('page.store'), $this->payload(['title' => '404']))
            ->assertSessionHasErrors('title');

        $this->assertSame(0, Page::query()->where('slug', 'admin')->count());
        $this->assertSame(0, Page::query()->where('slug', '404')->count());
    }

    public function test_unknown_block_types_are_rejected(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('page.store'), $this->payload([
            'blocks' => [['id' => 'x', 'type' => 'video']],
        ]))->assertSessionHasErrors('blocks.0.type');

        $this->assertSame(0, Page::count());
    }

    public function test_updating_keeps_the_original_slug(): void
    {
        $this->actingAs(User::factory()->create());
        $page = $this->existingPage();

        $this->patch(route('page.update', $page), [
            'title' => 'Título novo',
            'slug' => $page->slug,
            'blocks' => [['id' => 'b1', 'type' => 'text', 'body' => 'Conteúdo novo']],
        ])->assertRedirect();

        $page->refresh();

        $this->assertSame('Título novo', $page->title);
        $this->assertSame('existente', $page->slug);
        $this->assertSame('Conteúdo novo', $page->blocks[0]['body']);
    }

    public function test_publishing_sets_status_and_snapshots_a_revision(): void
    {
        $this->actingAs(User::factory()->create());
        $page = $this->existingPage();

        $this->post(route('page.publish', $page))->assertRedirect();

        $page->refresh();

        $this->assertSame(PageStatus::Published, $page->status);
        $this->assertNotNull($page->published_at);
        $this->assertSame(1, $page->revisions()->count());
        $this->assertSame('oi', $page->revisions()->first()->blocks[0]['body']);
    }

    public function test_a_future_publication_is_scheduled(): void
    {
        $this->actingAs(User::factory()->create());
        $page = $this->existingPage();

        $this->post(route('page.publish', $page), [
            'published_at' => now()->addDay()->toDateTimeString(),
        ])->assertRedirect();

        $this->assertSame(PageStatus::Scheduled, $page->refresh()->status);
    }

    public function test_unpublishing_returns_to_draft(): void
    {
        $this->actingAs(User::factory()->create());
        $page = $this->existingPage([
            'status' => PageStatus::Published,
            'published_at' => now()->subMinute(),
        ]);

        $this->post(route('page.unpublish', $page))->assertRedirect();

        $page->refresh();

        $this->assertSame(PageStatus::Draft, $page->status);
        $this->assertNull($page->published_at);
    }

    public function test_a_revision_can_be_restored(): void
    {
        $this->actingAs(User::factory()->create());
        $page = $this->existingPage();

        $this->post(route('page.publish', $page));
        $revision = $page->revisions()->firstOrFail();

        $this->patch(route('page.update', $page), [
            'title' => 'Alterado',
            'slug' => $page->slug,
            'blocks' => [['id' => 'b1', 'type' => 'text', 'body' => 'versão 2']],
        ]);

        $this->post(route('page.revisions.restore', [$page, $revision]))
            ->assertRedirect();

        $page->refresh();

        $this->assertSame('Existente', $page->title);
        $this->assertSame('oi', $page->blocks[0]['body']);
        $this->assertSame(2, $page->revisions()->count());
    }

    public function test_preview_requires_auth_and_renders_the_page_component(): void
    {
        $this->actingAs(User::factory()->create());
        $page = $this->existingPage();

        $this->get(route('page.preview', $page))
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->component('Page::Preview', false)
                ->where('page.slug', 'existente')
            );
    }

    public function test_a_page_can_be_deleted(): void
    {
        $this->actingAs(User::factory()->create());
        $page = $this->existingPage();

        $this->delete(route('page.destroy', $page))
            ->assertRedirect(route('page.index'));

        $this->assertSame(0, Page::query()->where('slug', 'existente')->count());
    }
}
