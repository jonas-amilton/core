<?php

declare(strict_types=1);

namespace Modules\Media\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Modules\Blog\Models\Post;
use Modules\Catalog\Models\Product;
use Modules\Media\Models\Media;
use Modules\Page\Enums\PageStatus;
use Modules\Page\Models\Page;
use Tests\TestCase;

class MediaManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    public function test_guests_are_redirected_from_every_media_route(): void
    {
        $this->get(route('media.index'))->assertRedirect(route('login'));
        $this->get(route('media.create'))->assertRedirect(route('login'));
        $this->get(route('media.picker'))->assertRedirect(route('login'));
    }

    public function test_the_library_lists_uploaded_media(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('media.store'), [
            'file' => UploadedFile::fake()->image('foto.jpg', 800, 600),
        ]);

        $this->get(route('media.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->component('Media::Index', false)
                ->has('media', 1)
                ->where('media.0.original_name', 'foto.jpg')
                ->where('media.0.kind', 'image')
            );
    }

    public function test_a_file_can_be_uploaded(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('media.store'), [
            'file' => UploadedFile::fake()->image('banner.png', 1200, 630),
            'alt' => 'Banner da home',
        ])->assertRedirect(route('media.index'));

        $media = Media::firstOrFail();

        Storage::disk('public')->assertExists($media->path);
        $this->assertSame('banner.png', $media->original_name);
        $this->assertSame('Banner da home', $media->alt);
        $this->assertSame('image', $media->kind()->value);
        $this->assertSame(1, Media::count());
    }

    public function test_unsupported_files_are_rejected(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('media.store'), [
            'file' => UploadedFile::fake()->create('script.svg', 4, 'image/svg+xml'),
        ])->assertSessionHasErrors('file');

        $this->assertSame(0, Media::count());
    }

    public function test_files_above_the_limit_are_rejected(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('media.store'), [
            'file' => UploadedFile::fake()->create('grande.pdf', 11000, 'application/pdf'),
        ])->assertSessionHasErrors('file');

        $this->assertSame(0, Media::count());
    }

    public function test_alt_and_caption_can_be_edited(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('media.store'), [
            'file' => UploadedFile::fake()->image('foto.jpg'),
        ]);

        $media = Media::firstOrFail();

        $this->patch(route('media.update', $media), [
            'alt' => 'Pessoa sorrindo',
            'caption' => 'Equipe em 2026',
        ])->assertRedirect();

        $media->refresh();

        $this->assertSame('Pessoa sorrindo', $media->alt);
        $this->assertSame('Equipe em 2026', $media->caption);
    }

    public function test_deleting_removes_the_row_and_the_file(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('media.store'), [
            'file' => UploadedFile::fake()->image('foto.jpg'),
        ]);

        $media = Media::firstOrFail();
        $path = $media->path;

        $this->delete(route('media.destroy', $media))
            ->assertRedirect(route('media.index'));

        $this->assertSame(0, Media::count());
        Storage::disk('public')->assertMissing($path);
    }

    public function test_media_used_by_a_page_cannot_be_deleted(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('media.store'), [
            'file' => UploadedFile::fake()->image('foto.jpg'),
        ]);

        $media = Media::firstOrFail();

        Page::create([
            'title' => 'Com imagem',
            'slug' => 'com-imagem',
            'blocks' => [[
                'id' => 'i1',
                'type' => 'image',
                'src' => $media->url(),
                'media_id' => $media->id,
            ]],
            'status' => PageStatus::Draft,
        ]);

        $this->delete(route('media.destroy', $media))
            ->assertRedirect()
            ->assertSessionHasErrors('media');

        $this->assertDatabaseHas('media', ['id' => $media->id]);
        Storage::disk('public')->assertExists($media->path);
    }

    public function test_media_used_as_a_blog_cover_cannot_be_deleted(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('media.store'), [
            'file' => UploadedFile::fake()->image('capa.jpg'),
        ]);

        $media = Media::firstOrFail();

        Post::factory()->create(['slug' => 'capa-media', 'cover' => $media->url()]);

        $this->delete(route('media.destroy', $media))
            ->assertRedirect()
            ->assertSessionHasErrors('media');

        $this->assertDatabaseHas('media', ['id' => $media->id]);
        Storage::disk('public')->assertExists($media->path);
    }

    public function test_media_used_by_a_product_cannot_be_deleted(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('media.store'), [
            'file' => UploadedFile::fake()->image('produto.jpg'),
        ]);

        $media = Media::firstOrFail();

        Product::factory()->create(['slug' => 'produto-media'])->media()->attach($media->id);

        $this->delete(route('media.destroy', $media))
            ->assertRedirect()
            ->assertSessionHasErrors('media');

        $this->assertDatabaseHas('media', ['id' => $media->id]);
        Storage::disk('public')->assertExists($media->path);
    }

    public function test_the_picker_returns_images_as_json(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('media.store'), [
            'file' => UploadedFile::fake()->image('foto.jpg'),
        ]);
        $this->post(route('media.store'), [
            'file' => UploadedFile::fake()->create('doc.pdf', 10, 'application/pdf'),
        ]);

        $response = $this->getJson(route('media.picker'))->assertOk();

        $this->assertCount(1, $response->json('data'));
        $this->assertSame('foto.jpg', $response->json('data.0.original_name'));
    }

    public function test_a_file_rejected_by_php_ini_explains_the_server_limit(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('media.store'), [])
            ->assertSessionHasErrors([
                'file' => 'Nenhum arquivo chegou ao servidor. Se você selecionou um, ele passa do limite do PHP ('
                    .ini_get('upload_max_filesize').' por arquivo, '.ini_get('post_max_size').' por requisição).',
            ]);
    }

    public function test_the_mime_type_is_detected_from_the_content(): void
    {
        $this->actingAs(User::factory()->create());

        $path = tempnam(sys_get_temp_dir(), 'media');
        $this->assertIsString($path);
        file_put_contents(
            $path,
            base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8DwHwAFAAH/q842iQAAAABJRU5ErkJggg=='),
        );

        $file = new UploadedFile($path, 'foto.jpg', 'application/octet-stream', null, true);

        $this->post(route('media.store'), ['file' => $file])
            ->assertRedirect(route('media.index'));

        $this->assertSame('image/png', Media::firstOrFail()->mime);
    }
}
