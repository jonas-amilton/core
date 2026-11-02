<?php

declare(strict_types=1);

namespace Modules\Store\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Store\Enums\StoreKind;

/**
 * @property int $id
 * @property string $name
 * @property StoreKind $kind
 * @property string|null $address
 * @property string|null $phone
 * @property string|null $url
 * @property int $position
 */
class Store extends Model
{
    protected $fillable = [
        'name',
        'kind',
        'address',
        'phone',
        'url',
        'position',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'kind' => StoreKind::class,
            'position' => 'integer',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toAdminArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'kind' => $this->kind->value,
            'kind_label' => $this->kind->label(),
            'address' => $this->address,
            'phone' => $this->phone,
            'url' => $this->url,
            'position' => $this->position,
        ];
    }
}
