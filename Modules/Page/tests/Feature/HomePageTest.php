<?php

declare(strict_types=1);

namespace Modules\Page\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Page\Enums\PageStatus;
use Modules\Page\Models\Page;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_home_page_is_provisioned_on_first_visit(): void
    {
        $this->get('/')->assertOk();

        $home = Page::query()->where('slug', 'home')->firstOrFail();

        $this->assertSame(PageStatus::Published, $home->status);
        $this->assertNotNull($home->published_at);
        $this->assertNotEmpty($home->blocks);
    }

    public function test_provisioning_is_idempotent(): void
    {
        $this->get('/');
        $this->get('/home');
        $this->get('/');

        $this->assertSame(1, Page::query()->where('slug', 'home')->count());
    }

    public function test_the_home_page_cannot_be_deleted(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->get('/'); // provisiona
        $home = Page::query()->where('slug', 'home')->firstOrFail();

        $this->delete(route('page.destroy', $home))
            ->assertRedirect()
            ->assertSessionHasErrors('page');

        $this->assertDatabaseHas('pages', ['id' => $home->id]);
    }

    public function test_the_home_page_cannot_be_unpublished(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get('/');
        $home = Page::query()->where('slug', 'home')->firstOrFail();

        $this->post(route('page.unpublish', $home))
            ->assertRedirect()
            ->assertSessionHasErrors('page');

        $this->assertSame(PageStatus::Published, $home->refresh()->status);
    }

    public function test_the_home_page_can_be_edited(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get('/');
        $home = Page::query()->where('slug', 'home')->firstOrFail();

        $this->patch(route('page.update', $home), [
            'title' => 'Página inicial',
            'slug' => 'home',
            'blocks' => [
                ['id' => 'h1', 'type' => 'text', 'body' => 'Novo conteúdo'],
            ],
        ])->assertRedirect();

        $home->refresh();

        $this->assertSame('Página inicial', $home->title);
        $this->assertSame('home', $home->slug);
        $this->assertSame('Novo conteúdo', $home->blocks[0]['body']);
    }

    public function test_the_admin_list_marks_the_home_page(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get('/');

        Page::create([
            'title' => 'Mais nova',
            'slug' => 'mais-nova',
            'blocks' => [],
            'status' => PageStatus::Draft,
        ]);

        $this->get(route('page.index'))
            ->assertInertia(fn ($inertia) => $inertia
                ->component('Page::Index', false)
                ->has('pages', 3)
                ->where('pages.0.is_home', true)
            );
    }
}
