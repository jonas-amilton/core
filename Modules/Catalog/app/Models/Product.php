<?php

declare(strict_types=1);

namespace Modules\Catalog\Models;

use App\Support\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Modules\Media\Models\Media;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $sku
 * @property string|null $price
 * @property string|null $description
 * @property array<string, array<string, mixed>>|null $translations
 * @property int $position
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Product extends Model
{
    use Translatable;

    protected $table = 'catalog_products';

    protected $fillable = [
        'name',
        'slug',
        'sku',
        'price',
        'description',
        'translations',
        'position',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'position' => 'integer',
            'price' => 'decimal:2',
            'translations' => 'array',
        ];
    }

    /**
     * @return BelongsToMany<Category, $this>
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'catalog_category_product');
    }

    /**
     * @return BelongsToMany<Media, $this>
     */
    public function media(): BelongsToMany
    {
        return $this->belongsToMany(Media::class, 'catalog_product_media')
            ->withPivot('position')
            ->orderBy('catalog_product_media.position');
    }

    /**
     * @return BelongsToMany<Product, $this>
     */
    public function related(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'catalog_related', 'product_id', 'related_id');
    }

    public function cover(): ?string
    {
        return $this->media->first()?->url();
    }

    /**
     * @return array<int, string>
     */
    public function imageUrls(): array
    {
        return $this->media->map(fn (Media $media): string => $media->url())->values()->all();
    }

    /**
     * @return array<string, mixed>
     */
    public function toAdminArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'sku' => $this->sku,
            'price' => $this->price,
            'position' => $this->position,
            'cover' => $this->cover(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toSiteArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->transString('name') ?? $this->name,
            'slug' => $this->slug,
            'sku' => $this->sku,
            'price' => $this->price,
            'cover' => $this->cover(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toEditorArray(): array
    {
        return [
            ...$this->toAdminArray(),
            'description' => $this->description,
            'media' => $this->media
                ->map(fn (Media $media): array => [
                    'id' => $media->id,
                    'url' => $media->url(),
                    'alt' => $media->alt,
                    'original_name' => $media->original_name,
                ])
                ->values()
                ->all(),
            'category_ids' => $this->categories->pluck('id')->all(),
            'related_ids' => $this->related->pluck('id')->all(),
            'translations' => $this->editorTranslations(['name', 'description']),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toPublicArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->transString('name') ?? $this->name,
            'slug' => $this->slug,
            'sku' => $this->sku,
            'price' => $this->price,
            'description' => $this->transString('description') ?? $this->description,
            'images' => $this->imageUrls(),
            'categories' => $this->categories
                ->map(fn (Category $category): array => [
                    'name' => $category->transString('name') ?? $category->name,
                    'slug' => $category->slug,
                ])
                ->values()
                ->all(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toCardArray(): array
    {
        return [
            'title' => $this->transString('name') ?? $this->name,
            'description' => $this->sku,
            'price' => $this->price,
            'url' => '/produto/'.$this->slug,
            'cover' => $this->cover(),
        ];
    }
}
