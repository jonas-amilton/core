<?php

declare(strict_types=1);

namespace Modules\Store\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Store\Contracts\StoreRepository;
use Modules\Store\Data\StoreData;
use Modules\Store\Models\Store;

final class EloquentStoreRepository implements StoreRepository
{
    /**
     * @return Collection<int, Store>
     */
    public function all(): Collection
    {
        return Store::query()
            ->orderBy('position')
            ->orderBy('name')
            ->get();
    }

    public function find(int $id): ?Store
    {
        return Store::query()->find($id);
    }

    public function create(StoreData $data): Store
    {
        return Store::query()->create($this->attributes($data));
    }

    public function update(Store $store, StoreData $data): Store
    {
        $store->update($this->attributes($data));

        return $store->refresh();
    }

    public function delete(Store $store): void
    {
        $store->delete();
    }

    /**
     * @return array<string, mixed>
     */
    private function attributes(StoreData $data): array
    {
        return [
            'name' => $data->name,
            'kind' => $data->kind->value,
            'address' => $data->address,
            'phone' => $data->phone,
            'url' => $data->url,
            'position' => $data->position,
        ];
    }
}
