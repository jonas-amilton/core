<?php

declare(strict_types=1);

namespace Modules\Content\Models;

use App\Support\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $author
 * @property string|null $role
 * @property string $quote
 * @property array<string, array<string, mixed>>|null $translations
 * @property string|null $avatar
 * @property int $sort_order
 * @property Carbon|null $created_at
 */
class Testimonial extends Model
{
    use Translatable;

    protected $fillable = [
        'author',
        'role',
        'quote',
        'avatar',
        'translations',
        'sort_order',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'translations' => 'array',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toAdminArray(): array
    {
        return [
            'id' => $this->id,
            'author' => $this->author,
            'role' => $this->role,
            'quote' => $this->quote,
            'avatar' => $this->avatar,
            'sort_order' => $this->sort_order,
            'translations' => $this->editorTranslations(['role', 'quote']),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toCardArray(): array
    {
        return [
            'title' => $this->author,
            'description' => $this->transString('quote') ?? $this->quote,
            'role' => $this->transString('role') ?? $this->role,
            'avatar' => $this->avatar,
        ];
    }
}
