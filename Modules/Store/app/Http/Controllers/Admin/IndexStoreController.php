<?php

declare(strict_types=1);

namespace Modules\Store\Http\Controllers\Admin;

use Inertia\Inertia;
use Inertia\Response;
use Modules\Store\Enums\StoreKind;
use Modules\Store\Models\Store;
use Modules\Store\Services\StoreService;

final class IndexStoreController
{
    public function __invoke(StoreService $stores): Response
    {
        return Inertia::render('Store::Index', [
            'stores' => $stores->all()
                ->map(fn (Store $store): array => $store->toAdminArray())
                ->all(),
            'kinds' => array_map(
                fn (StoreKind $kind): array => ['value' => $kind->value, 'label' => $kind->label()],
                StoreKind::cases(),
            ),
        ]);
    }
}
