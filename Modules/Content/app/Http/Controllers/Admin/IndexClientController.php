<?php

declare(strict_types=1);

namespace Modules\Content\Http\Controllers\Admin;

use Inertia\Inertia;
use Inertia\Response;
use Modules\Content\Models\Client;
use Modules\Content\Services\ContentService;

final class IndexClientController
{
    public function __invoke(ContentService $content): Response
    {
        return Inertia::render('Content::Clients', [
            'clients' => $content->clients()
                ->map(fn (Client $client): array => $client->toAdminArray())
                ->all(),
        ]);
    }
}
