<?php

declare(strict_types=1);

namespace Modules\Quote\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $code
 * @property int $percent
 * @property bool $active
 * @property Carbon|null $expires_at
 */
class Coupon extends Model
{
    protected $fillable = [
        'code',
        'percent',
        'active',
        'expires_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'percent' => 'integer',
            'active' => 'boolean',
            'expires_at' => 'datetime',
        ];
    }

    public function isValid(): bool
    {
        return $this->active
            && ($this->expires_at === null || $this->expires_at->isFuture());
    }

    public function discountFor(float $subtotal): float
    {
        return round($subtotal * $this->percent / 100, 2);
    }

    /**
     * @return array<string, mixed>
     */
    public function toAdminArray(): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'percent' => $this->percent,
            'active' => $this->active,
            'expires_at' => $this->expires_at?->toIso8601String(),
            'valid' => $this->isValid(),
        ];
    }
}
