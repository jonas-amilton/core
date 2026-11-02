<?php

declare(strict_types=1);

namespace Modules\Page\Models;

use App\Support\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Modules\Page\Enums\PageStatus;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $icon
 * @property bool $is_service
 * @property array<int, array<string, mixed>> $blocks
 * @property array<string, array<string, mixed>>|null $translations
 * @property PageStatus $status
 * @property Carbon|null $published_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Page extends Model
{
    use Translatable;

    public const HOME_SLUG = 'home';

    public const NOT_FOUND_SLUG = '404';

    /**
     * Pages owned by the system: always present, editable, never deleted.
     *
     * @var array<int, string>
     */
    public const SYSTEM_SLUGS = [self::HOME_SLUG, self::NOT_FOUND_SLUG];

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'is_service' => false,
    ];

    protected $fillable = [
        'title',
        'slug',
        'icon',
        'is_service',
        'blocks',
        'translations',
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
            'translations' => 'array',
            'is_service' => 'boolean',
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
            'icon' => $this->icon,
            'is_service' => $this->is_service,
            'status' => $this->status->value,
            'published_at' => $this->published_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
            'is_home' => $this->isHome(),
            'is_not_found' => $this->isNotFound(),
            'is_system' => $this->isSystem(),
        ];
    }

    /**
     * Blocks for the active language, falling back to the pt-BR base.
     *
     * @return array<int, array<string, mixed>>
     */
    public function blocksFor(?string $locale = null): array
    {
        $locale ??= (string) app()->getLocale();
        $blocks = $this->storedBlocks($locale);

        return $blocks !== [] ? $blocks : array_values($this->blocks ?? []);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function storedBlocks(string $locale): array
    {
        $translations = is_array($this->translations) ? $this->translations : [];
        $blocks = $translations[$locale]['blocks'] ?? null;

        return is_array($blocks) ? array_values($blocks) : [];
    }

    /**
     * Raw per-language values for the editor, without the pt-BR fallback so an
     * empty tab stays empty until the user writes the translation.
     *
     * @return array<string, array{title: string|null, blocks: array<int, array<string, mixed>>}>
     */
    public function translationsForEditor(): array
    {
        $translations = is_array($this->translations) ? $this->translations : [];
        $title = fn (string $locale): ?string => isset($translations[$locale]['title'])
            && is_string($translations[$locale]['title'])
            && trim($translations[$locale]['title']) !== ''
                ? $translations[$locale]['title']
                : null;

        return [
            'en' => [
                'title' => $title('en'),
                'blocks' => $this->storedBlocks('en'),
            ],
            'es' => [
                'title' => $title('es'),
                'blocks' => $this->storedBlocks('es'),
            ],
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
            'translations' => $this->translationsForEditor(),
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
            'title' => $this->transString('title') ?? $this->title,
            'blocks' => array_values(array_filter(
                $this->blocksFor(),
                fn (array $block): bool => empty($block['hidden']),
            )),
        ];
    }
}
