<?php

declare(strict_types=1);

namespace Modules\Page\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Modules\Page\Enums\PageStatus;
use Modules\Page\Models\Page;
use Tests\TestCase;

class NotFoundPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_404_page_is_provisioned_on_the_first_miss(): void
    {
        $this->get('/nao-existe')->assertNotFound();

        $page = Page::query()->where('slug', '404')->firstOrFail();

        $this->assertSame(PageStatus::Published, $page->status);
        $this->assertNotEmpty($page->blocks);
    }

    public function test_a_missing_url_renders_the_404_page(): void
    {
        $this->get('/nao-existe')
            ->assertNotFound()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->component('Site::Show', false)
                ->where('page.slug', '404')
                ->where('page.title', 'Página não encontrada')
                ->has('page.blocks', 1)
            );
    }

    public function test_the_404_page_answers_with_a_404_status_on_its_own_slug(): void
    {
        $this->get('/404')->assertNotFound();
    }

    public function test_the_404_page_is_not_listed_in_the_site_navigation(): void
    {
        $this->get('/nao-existe'); // provisiona a 404
        $this->get('/');           // provisiona a home

        $this->get('/')
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->component('Site::Show', false)
                ->has('navigation', 1)
                ->where('navigation.0.slug', 'home')
            );
    }

    public function test_the_404_page_cannot_be_deleted(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get('/nao-existe');
        $page = Page::query()->where('slug', '404')->firstOrFail();

        $this->delete(route('page.destroy', $page))
            ->assertRedirect()
            ->assertSessionHasErrors('page');

        $this->assertDatabaseHas('pages', ['id' => $page->id]);
    }

    public function test_the_404_page_cannot_be_unpublished(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get('/nao-existe');
        $page = Page::query()->where('slug', '404')->firstOrFail();

        $this->post(route('page.unpublish', $page))
            ->assertRedirect()
            ->assertSessionHasErrors('page');

        $this->assertSame(PageStatus::Published, $page->refresh()->status);
    }

    public function test_the_404_page_can_be_edited(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get('/nao-existe');
        $page = Page::query()->where('slug', '404')->firstOrFail();

        $this->patch(route('page.update', $page), [
            'title' => 'Ops, nada por aqui',
            'slug' => '404',
            'blocks' => [
                ['id' => 'nf1', 'type' => 'text', 'body' => 'Conteúdo personalizado'],
            ],
        ])->assertRedirect();

        $page->refresh();

        $this->assertSame('Ops, nada por aqui', $page->title);
        $this->assertSame('Conteúdo personalizado', $page->blocks[0]['body']);

        $this->get('/sumiu')
            ->assertNotFound()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->where('page.title', 'Ops, nada por aqui')
            );
    }
}
