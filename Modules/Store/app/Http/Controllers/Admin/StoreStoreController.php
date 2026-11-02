<?php

declare(strict_types=1);

namespace Modules\Store\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Modules\Store\Http\Requests\StoreRequest;
use Modules\Store\Services\StoreService;

final class StoreStoreController
{
    public function __invoke(StoreRequest $request, StoreService $stores): RedirectResponse
    {
        $stores->create($request->toData());

        return to_route('stores.index');
    }
}
