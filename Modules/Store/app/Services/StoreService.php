<?php

declare(strict_types=1);

namespace Modules\Store\Services;

use Illuminate\Database\Eloquent\Collection;
use Modules\Store\Contracts\StoreRepository;
use Modules\Store\Data\StoreData;
use Modules\Store\Models\Store;

final class StoreService
{
    public function __construct(private StoreRepository $stores) {}

    /**
     * @return Collection<int, Store>
     */
    public function all(): Collection
    {
        return $this->stores->all();
    }

    public function create(StoreData $data): Store
    {
        return $this->stores->create($data);
    }

    public function update(Store $store, StoreData $data): Store
    {
        return $this->stores->update($store, $data);
    }

    public function delete(Store $store): void
    {
        $this->stores->delete($store);
    }
}
