<?php

declare(strict_types=1);

namespace Modules\Blog\Models;

use App\Support\Searchable;
use App\Support\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int|null $category_id
 * @property string $title
 * @property string $slug
 * @property string|null $excerpt
 * @property string $body
 * @property array<string, array<string, mixed>>|null $translations
 * @property string|null $cover
 * @property Carbon|null $published_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Post extends Model
{
    use Searchable;
    use Translatable;

    protected $table = 'blog_posts';

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'excerpt',
        'body',
        'cover',
        'translations',
        'published_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'translations' => 'array',
        ];
    }

    /**
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    protected function searchableText(): string
    {
        return implode(' ', array_filter([$this->title, $this->excerpt, $this->body]));
    }

    public function isPublished(): bool
    {
        return $this->published_at !== null && $this->published_at->isPast();
    }

    /**
     * @return array<string, mixed>
     */
    public function toAdminArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'category_id' => $this->category_id,
            'published_at' => $this->published_at?->toIso8601String(),
            'cover' => $this->cover,
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toEditorArray(): array
    {
        return [
            ...$this->toAdminArray(),
            'excerpt' => $this->excerpt,
            'body' => $this->body,
            'translations' => $this->editorTranslations(['title', 'excerpt', 'body']),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toPublicArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->transString('title') ?? $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->transString('excerpt') ?? $this->excerpt,
            'body' => $this->transString('body') ?? $this->body,
            'cover' => $this->cover,
            'published_at' => $this->published_at?->toIso8601String(),
            'category' => $this->category !== null
                ? ($this->category->transString('name') ?? $this->category->name)
                : null,
            'category_slug' => $this->category?->slug,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toCardArray(): array
    {
        return [
            'title' => $this->transString('title') ?? $this->title,
            'description' => $this->transString('excerpt') ?? $this->excerpt,
            'url' => '/blog/'.$this->slug,
            'cover' => $this->cover,
            'category' => $this->category !== null
                ? ($this->category->transString('name') ?? $this->category->name)
                : null,
            'date' => $this->published_at?->format('d/m'),
        ];
    }
}
