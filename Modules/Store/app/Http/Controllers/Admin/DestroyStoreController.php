<?php

declare(strict_types=1);

namespace Modules\Store\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Modules\Store\Models\Store;
use Modules\Store\Services\StoreService;

final class DestroyStoreController
{
    public function __invoke(Store $store, StoreService $stores): RedirectResponse
    {
        $stores->delete($store);

        return to_route('stores.index');
    }
}
