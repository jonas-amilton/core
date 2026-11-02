<?php

declare(strict_types=1);

namespace Modules\Contact\Http\Controllers\Site;

use Illuminate\Http\RedirectResponse;
use Modules\Contact\Http\Requests\ContactMessageRequest;
use Modules\Contact\Services\ContactService;

final class StoreContactMessageController
{
    public function __invoke(ContactMessageRequest $request, ContactService $contact): RedirectResponse
    {
        $contact->storeMessage($request->toData());

        return back()->with('contact_status', 'Mensagem enviada. Retornaremos em breve.');
    }
}
