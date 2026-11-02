<?php

declare(strict_types=1);

namespace Modules\Store\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Modules\Store\Http\Requests\StoreRequest;
use Modules\Store\Models\Store;
use Modules\Store\Services\StoreService;

final class UpdateStoreController
{
    public function __invoke(StoreRequest $request, Store $store, StoreService $stores): RedirectResponse
    {
        $stores->update($store, $request->toData());

        return to_route('stores.index');
    }
}
