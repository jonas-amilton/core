<?php

declare(strict_types=1);

namespace Modules\Content\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Modules\Content\Models\Client;
use Modules\Content\Models\Faq;
use Modules\Content\Models\Testimonial;
use Modules\Page\Enums\PageStatus;
use Modules\Page\Models\Page;
use Modules\Page\Services\BlockContentResolver;
use Tests\TestCase;

class ContentManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_manage_content(): void
    {
        $this->get(route('testimonial.index'))->assertRedirect(route('login'));
        $this->post(route('faq.store'), [])->assertRedirect(route('login'));
    }

    public function test_testimonials_can_be_created_updated_and_deleted(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('testimonial.store'), [
            'author' => 'Maria',
            'role' => 'Diretora',
            'quote' => 'Atendimento excelente.',
            'sort_order' => 1,
        ])->assertRedirect(route('testimonial.index'));

        $testimonial = Testimonial::query()->firstOrFail();
        $this->assertSame('Maria', $testimonial->author);

        $this->patch(route('testimonial.update', $testimonial), [
            'author' => 'Maria Silva',
            'quote' => 'Texto novo.',
        ])->assertRedirect();

        $this->assertSame('Maria Silva', $testimonial->refresh()->author);

        $this->delete(route('testimonial.destroy', $testimonial))->assertRedirect();

        $this->assertSame(0, Testimonial::query()->count());
    }

    public function test_faqs_and_clients_are_listed_with_content(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('faq.store'), [
            'question' => 'Como funciona?',
            'answer' => 'Assim.',
        ]);

        $this->post(route('client.store'), [
            'name' => 'Empresa X',
            'logo' => '/storage/logo.png',
        ]);

        $this->get(route('faq.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->component('Content::Faqs', false)
                ->has('faqs', 1)
                ->where('faqs.0.question', 'Como funciona?')
            );

        $this->get(route('client.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->component('Content::Clients', false)
                ->has('clients', 1)
            );
    }

    public function test_required_fields_are_validated(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('faq.store'), ['question' => ''])
            ->assertSessionHasErrors(['question', 'answer']);

        $this->assertSame(0, Faq::query()->count());
    }

    public function test_faqs_and_testimonials_translate_on_the_site(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('faq.store'), [
            'question' => 'Como funciona?',
            'answer' => 'Assim.',
            'translations' => [
                'en' => [
                    'question' => 'How does it work?',
                    'answer' => 'Like this.',
                ],
            ],
        ]);

        $this->post(route('testimonial.store'), [
            'author' => 'Maria',
            'role' => 'Diretora',
            'quote' => 'Muito bom.',
            'translations' => [
                'en' => ['role' => 'Director', 'quote' => 'Very good.'],
            ],
        ]);

        Page::query()->create([
            'title' => 'Contábil',
            'slug' => 'contabil',
            'blocks' => [
                ['id' => 'b1', 'type' => 'testimonials'],
                ['id' => 'b2', 'type' => 'faq'],
            ],
            'status' => PageStatus::Published,
            'published_at' => now()->subMinute(),
        ]);

        $this->get('/contabil?locale=en')
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->where('page.blocks.0.items.0.description', 'Very good.')
                ->where('page.blocks.0.items.0.role', 'Director')
                ->where('page.blocks.1.items.0.title', 'How does it work?')
                ->where('page.blocks.1.items.0.description', 'Like this.')
            );

        $this->get('/contabil?locale=es')
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->where('page.blocks.1.items.0.title', 'Como funciona?')
            );
    }

    public function test_the_block_resolver_attaches_live_content(): void
    {
        Testimonial::query()->create([
            'author' => 'Maria',
            'quote' => 'Muito bom.',
            'sort_order' => 0,
        ]);
        Faq::query()->create([
            'question' => 'Pergunta?',
            'answer' => 'Resposta.',
            'sort_order' => 0,
        ]);
        Client::query()->create(['name' => 'Cliente', 'sort_order' => 0]);
        Page::query()->create([
            'title' => 'Contábil',
            'slug' => 'contabil',
            'icon' => '/storage/icon.png',
            'is_service' => true,
            'blocks' => [],
            'status' => PageStatus::Published,
            'published_at' => now()->subMinute(),
        ]);

        $blocks = app(BlockContentResolver::class)->resolve([
            ['id' => 'b1', 'type' => 'services', 'title' => 'Áreas'],
            ['id' => 'b2', 'type' => 'testimonials', 'title' => 'Depoimentos'],
            ['id' => 'b3', 'type' => 'faq', 'title' => 'FAQ'],
            ['id' => 'b4', 'type' => 'clients', 'title' => 'Clientes'],
        ]);

        $this->assertSame('Contábil', $blocks[0]['items'][0]['title']);
        $this->assertSame('/contabil', $blocks[0]['items'][0]['url']);
        $this->assertSame('Maria', $blocks[1]['items'][0]['title']);
        $this->assertSame('Pergunta?', $blocks[2]['items'][0]['title']);
        $this->assertSame('Cliente', $blocks[3]['items'][0]['title']);
    }
}
