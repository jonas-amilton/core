<?php

declare(strict_types=1);

namespace Modules\Store\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Modules\Store\Data\StoreData;
use Modules\Store\Models\Store;

interface StoreRepository
{
    /**
     * @return Collection<int, Store>
     */
    public function all(): Collection;

    public function find(int $id): ?Store;

    public function create(StoreData $data): Store;

    public function update(Store $store, StoreData $data): Store;

    public function delete(Store $store): void;
}
