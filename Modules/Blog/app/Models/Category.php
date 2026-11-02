<?php

declare(strict_types=1);

namespace Modules\Blog\Models;

use App\Support\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property Carbon|null $created_at
 */
class Category extends Model
{
    use Translatable;

    protected $table = 'blog_categories';

    protected $fillable = [
        'name',
        'slug',
        'translations',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'translations' => 'array',
        ];
    }

    /**
     * @return HasMany<Post, $this>
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'category_id');
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
            'name' => $this->transString('name') ?? $this->name,
            'slug' => $this->slug,
        ];
    }
}
