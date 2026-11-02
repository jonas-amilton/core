<?php

declare(strict_types=1);

namespace Modules\Contact\Http\Controllers\Site;

use Illuminate\Http\RedirectResponse;
use Modules\Contact\Http\Requests\CareerMessageRequest;
use Modules\Contact\Services\ContactService;

final class StoreCareerMessageController
{
    public function __invoke(CareerMessageRequest $request, ContactService $contact): RedirectResponse
    {
        $contact->storeMessage($request->toData());

        return back()->with('contact_status', 'Currículo recebido. Entraremos em contato.');
    }
}
