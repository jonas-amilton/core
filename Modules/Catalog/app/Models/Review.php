<?php

declare(strict_types=1);

namespace Modules\Catalog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $product_id
 * @property int|null $user_id
 * @property string $name
 * @property string $email
 * @property int $rating
 * @property string $body
 * @property bool $approved
 * @property Carbon|null $created_at
 */
class Review extends Model
{
    protected $table = 'catalog_reviews';

    protected $fillable = [
        'product_id',
        'user_id',
        'name',
        'email',
        'rating',
        'body',
        'approved',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'approved' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return array<string, mixed>
     */
    public function toSiteArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'rating' => $this->rating,
            'body' => $this->body,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toAdminArray(): array
    {
        return [
            ...$this->toSiteArray(),
            'email' => $this->email,
            'approved' => $this->approved,
            'product' => $this->product?->name,
        ];
    }
}
