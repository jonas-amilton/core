<?php

declare(strict_types=1);

namespace Modules\Content\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Modules\Content\Models\Client;
use Modules\Content\Services\ContentService;

final class DestroyClientController
{
    public function __invoke(Client $client, ContentService $content): RedirectResponse
    {
        $content->deleteClient($client);

        return to_route('client.index');
    }
}
