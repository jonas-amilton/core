<?php

declare(strict_types=1);

namespace Modules\Contact\Http\Controllers\Site;

use Illuminate\Http\RedirectResponse;
use Modules\Contact\Http\Requests\SubscriberRequest;
use Modules\Contact\Services\ContactService;

final class StoreSubscriberController
{
    public function __invoke(SubscriberRequest $request, ContactService $contact): RedirectResponse
    {
        $contact->subscribe((string) $request->input('email'));

        return back()->with('newsletter_status', 'Inscrição confirmada. Obrigado!');
    }
}
