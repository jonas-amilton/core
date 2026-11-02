<?php

declare(strict_types=1);

namespace Modules\Content\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Modules\Content\Http\Requests\ClientRequest;
use Modules\Content\Services\ContentService;

final class StoreClientController
{
    public function __invoke(ClientRequest $request, ContentService $content): RedirectResponse
    {
        $content->createClient($request->toData());

        return to_route('client.index');
    }
}
