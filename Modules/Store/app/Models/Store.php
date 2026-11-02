<?php

declare(strict_types=1);

namespace Modules\Store\Models;

use App\Support\Translatable;
use Illuminate\Database\Eloquent\Model;
use Modules\Store\Enums\StoreKind;

/**
 * @property int $id
 * @property string $name
 * @property StoreKind $kind
 * @property string|null $address
 * @property string|null $phone
 * @property string|null $url
 * @property array<string, array<string, mixed>>|null $translations
 * @property int $position
 */
class Store extends Model
{
    use Translatable;

    protected $fillable = [
        'name',
        'kind',
        'address',
        'phone',
        'url',
        'position',
        'translations',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'kind' => StoreKind::class,
            'position' => 'integer',
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
            'name' => $this->name,
            'kind' => $this->kind->value,
            'kind_label' => $this->kind->label(),
            'address' => $this->address,
            'phone' => $this->phone,
            'url' => $this->url,
            'position' => $this->position,
            'translations' => $this->editorTranslations(['name', 'address']),
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
            'kind_label' => $this->kind->label(),
            'address' => $this->transString('address') ?? $this->address,
            'phone' => $this->phone,
            'url' => $this->url,
        ];
    }
}
