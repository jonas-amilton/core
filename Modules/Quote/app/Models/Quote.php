<?php

declare(strict_types=1);

namespace Modules\Quote\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $phone
 * @property string|null $message
 * @property Carbon|null $answered_at
 * @property Carbon|null $created_at
 */
class Quote extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'message',
        'answered_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'answered_at' => 'datetime',
        ];
    }

    /**
     * @return HasMany<QuoteItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(QuoteItem::class);
    }

    /**
     * @return array<string, mixed>
     */
    public function toAdminArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'message' => $this->message,
            'answered' => $this->answered_at !== null,
            'created_at' => $this->created_at?->toIso8601String(),
            'items' => $this->items
                ->map(fn (QuoteItem $item): array => [
                    'id' => $item->id,
                    'name' => $item->name,
                    'quantity' => $item->quantity,
                ])
                ->values()
                ->all(),
        ];
    }
}
