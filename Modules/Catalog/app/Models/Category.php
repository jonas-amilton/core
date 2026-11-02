<?php

declare(strict_types=1);

namespace Modules\Catalog\Models;

use App\Support\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Modules\Catalog\Database\Factories\CategoryFactory;

/**
 * @property int $id
 * @property int|null $parent_id
 * @property string $name
 * @property array<string, array<string, mixed>>|null $translations
 * @property string $slug
 * @property int $position
 * @property Carbon|null $created_at
 */
class Category extends Model
{
    /** @use HasFactory<CategoryFactory> */
    use HasFactory, Translatable;

    protected $table = 'catalog_categories';

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
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
            'translations' => 'array',
        ];
    }

    /**
     * @return BelongsTo<Category, $this>
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * @return HasMany<Category, $this>
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('position')->orderBy('name');
    }

    /**
     * @return BelongsToMany<Product, $this>
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'catalog_category_product');
    }

    /**
     * @return array<string, mixed>
     */
    public function toAdminArray(): array
    {
        return [
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'position' => $this->position,
            'products_count' => $this->products_count ?? null,
            'translations' => $this->editorTranslations(['name']),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toSiteArray(): array
    {
        return [
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'name' => $this->transString('name') ?? $this->name,
            'slug' => $this->slug,
            'products_count' => $this->products_count ?? null,
        ];
    }

    protected static function newFactory(): CategoryFactory
    {
        return CategoryFactory::new();
    }
}
