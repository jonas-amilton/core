<?php

declare(strict_types=1);

namespace Modules\Blog\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Modules\Blog\Models\Category;
use Modules\Blog\Models\Post;
use Tests\TestCase;

class BlogManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function payload(array $overrides = []): array
    {
        return array_merge([
            'title' => 'Novo artigo',
            'slug' => null,
            'category_id' => null,
            'excerpt' => 'Resumo',
            'body' => 'Conteúdo do artigo.',
            'cover' => null,
            'published' => true,
        ], $overrides);
    }

    public function test_guests_cannot_manage_the_blog(): void
    {
        $this->get(route('blog.index'))->assertRedirect(route('login'));
        $this->post(route('blog.store'), $this->payload())->assertRedirect(route('login'));
    }

    public function test_a_published_post_gets_a_slug_and_appears_on_the_site(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('blog.store'), $this->payload())
            ->assertRedirect();

        $post = Post::query()->firstOrFail();

        $this->assertSame('novo-artigo', $post->slug);
        $this->assertTrue($post->isPublished());

        $this->get(route('blog.feed'))
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->component('Blog::Feed', false)
                ->has('posts', 1)
            );

        $this->get(route('blog.show', $post))
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->component('Blog::Article', false)
                ->where('post.title', 'Novo artigo')
            );
    }

    public function test_draft_posts_are_hidden_from_the_site(): void
    {
        $post = Post::query()->create([
            'title' => 'Rascunho',
            'slug' => 'rascunho',
            'body' => 'x',
            'published_at' => null,
        ]);

        $this->get(route('blog.show', $post))->assertNotFound();

        $this->get(route('blog.feed'))
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia->has('posts', 0));
    }

    public function test_slugs_are_unique(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('blog.store'), $this->payload());
        $this->post(route('blog.store'), $this->payload());

        $this->assertSame(
            ['novo-artigo', 'novo-artigo-2'],
            Post::query()->orderBy('id')->pluck('slug')->all(),
        );
    }

    public function test_categories_filter_the_feed_and_are_deleted_safely(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('blog.category.store'), ['name' => 'Tributação']);

        $category = Category::query()->firstOrFail();
        $this->post(route('blog.store'), $this->payload(['category_id' => $category->id]));

        $post = Post::query()->firstOrFail();

        $this->get(route('blog.category.show', $category))
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->component('Blog::Feed', false)
                ->has('posts', 1)
            );

        $this->delete(route('blog.category.destroy', $category))->assertRedirect();

        $this->assertNull($post->refresh()->category_id);
    }

    public function test_posts_and_categories_translate_on_the_site(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('blog.category.store'), [
            'name' => 'Notícias',
            'translations' => ['en' => ['name' => 'News']],
        ]);

        $category = Category::query()->firstOrFail();

        $this->post(route('blog.store'), $this->payload([
            'category_id' => $category->id,
            'translations' => [
                'en' => [
                    'title' => 'New article',
                    'excerpt' => 'Summary',
                    'body' => 'Article content.',
                ],
            ],
        ]));

        $post = Post::query()->firstOrFail();

        $this->get('/blog?locale=en')
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->where('posts.0.title', 'New article')
                ->where('posts.0.excerpt', 'Summary')
                ->where('posts.0.category', 'News')
                ->where('categories.0.name', 'News')
            );

        $this->get('/blog/'.$post->slug.'?locale=en')
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->where('post.title', 'New article')
                ->where('post.body', 'Article content.')
            );

        $this->get('/blog/'.$post->slug.'?locale=es')
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->where('post.title', 'Novo artigo')
            );

        $this->assertSame('New article', $post->translations['en']['title']);
    }

    public function test_admin_screens_render(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('blog.store'), $this->payload());
        $post = Post::query()->firstOrFail();

        $this->get(route('blog.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->component('Blog::Posts', false)
                ->has('posts', 1)
            );

        $this->get(route('blog.create'))
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->component('Blog::Editor', false)
                ->where('post', null)
            );

        $this->get(route('blog.edit', $post))
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->component('Blog::Editor', false)
                ->where('post.slug', 'novo-artigo')
            );

        $this->get(route('blog.category.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->component('Blog::Categories', false)
            );
    }

    public function test_unpublishing_on_update_clears_the_publication(): void
    {
        $this->actingAs(User::factory()->create());
        $this->post(route('blog.store'), $this->payload());

        $post = Post::query()->firstOrFail();

        $this->patch(route('blog.update', $post), $this->payload(['published' => false]))
            ->assertRedirect();

        $this->assertNull($post->refresh()->published_at);
    }
}
