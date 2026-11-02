<?php

declare(strict_types=1);

namespace Modules\Content\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $author
 * @property string|null $role
 * @property string $quote
 * @property string|null $avatar
 * @property int $sort_order
 * @property Carbon|null $created_at
 */
class Testimonial extends Model
{
    protected $fillable = [
        'author',
        'role',
        'quote',
        'avatar',
        'sort_order',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
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
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toCardArray(): array
    {
        return [
            'title' => $this->author,
            'description' => $this->quote,
            'role' => $this->role,
            'avatar' => $this->avatar,
        ];
    }
}
