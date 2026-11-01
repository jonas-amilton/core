<?php

declare(strict_types=1);

namespace Modules\Page\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Modules\Page\Enums\PageStatus;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property array<int, array<string, mixed>> $blocks
 * @property PageStatus $status
 * @property Carbon|null $published_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Page extends Model
{
    public const HOME_SLUG = 'home';

    public const NOT_FOUND_SLUG = '404';

    /**
     * Pages owned by the system: always present, editable, never deleted.
     *
     * @var array<int, string>
     */
    public const SYSTEM_SLUGS = [self::HOME_SLUG, self::NOT_FOUND_SLUG];

    protected $fillable = [
        'title',
        'slug',
        'blocks',
        'status',
        'published_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'blocks' => 'array',
            'status' => PageStatus::class,
            'published_at' => 'datetime',
        ];
    }

    /**
     * @return HasMany<PageRevision, $this>
     */
    public function revisions(): HasMany
    {
        return $this->hasMany(PageRevision::class)->latest('id');
    }

    public function isHome(): bool
    {
        return $this->slug === self::HOME_SLUG;
    }

    public function isNotFound(): bool
    {
        return $this->slug === self::NOT_FOUND_SLUG;
    }

    public function isSystem(): bool
    {
        return in_array($this->slug, self::SYSTEM_SLUGS, true);
    }

    public function isPublished(): bool
    {
        return $this->status === PageStatus::Published;
    }

    public function isPubliclyVisible(): bool
    {
        return $this->status->isVisible()
            && $this->published_at !== null
            && $this->published_at->isPast();
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
            'status' => $this->status->value,
            'published_at' => $this->published_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
            'is_home' => $this->isHome(),
            'is_not_found' => $this->isNotFound(),
            'is_system' => $this->isSystem(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toEditorArray(): array
    {
        return [
            ...$this->toAdminArray(),
            'blocks' => $this->blocks ?? [],
        ];
    }

    /**
     * Public payload: hidden blocks never reach the site.
     *
     * @return array<string, mixed>
     */
    public function toPublicArray(): array
    {
        return [
            ...$this->toAdminArray(),
            'blocks' => array_values(array_filter(
                $this->blocks ?? [],
                fn (array $block): bool => empty($block['hidden']),
            )),
        ];
    }
}
