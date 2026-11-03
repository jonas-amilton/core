<?php

declare(strict_types=1);

namespace Modules\Store\Http\Controllers\Site;

use Inertia\Inertia;
use Inertia\Response;
use Modules\Page\Services\SiteNavigation;
use Modules\Store\Enums\StoreKind;
use Modules\Store\Models\Store;
use Modules\Store\Services\StoreService;

final class IndexStoreController
{
    public function __invoke(StoreService $stores, SiteNavigation $navigation): Response
    {
        $all = $stores->all();

        return Inertia::render('Site::WhereToBuy', [
            'physical' => $all
                ->filter(fn (Store $store): bool => $store->kind === StoreKind::Physical)
                ->map(fn (Store $store): array => $store->toSiteArray())
                ->values()
                ->all(),
            'online' => $all
                ->filter(fn (Store $store): bool => $store->kind === StoreKind::Online)
                ->map(fn (Store $store): array => $store->toSiteArray())
                ->values()
                ->all(),
            'navigation' => $navigation->items(),
        ]);
    }
}
