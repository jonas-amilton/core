<?php

declare(strict_types=1);

namespace Modules\Content\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Modules\Content\Http\Requests\ClientRequest;
use Modules\Content\Models\Client;
use Modules\Content\Services\ContentService;

final class UpdateClientController
{
    public function __invoke(ClientRequest $request, Client $client, ContentService $content): RedirectResponse
    {
        $content->updateClient($client, $request->toData());

        return to_route('client.index');
    }
}
