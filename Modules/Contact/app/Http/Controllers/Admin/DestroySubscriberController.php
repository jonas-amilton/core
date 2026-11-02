<?php

declare(strict_types=1);

namespace Modules\Contact\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Modules\Contact\Models\Subscriber;
use Modules\Contact\Services\ContactService;

final class DestroySubscriberController
{
    public function __invoke(Subscriber $subscriber, ContactService $contact): RedirectResponse
    {
        $contact->deleteSubscriber($subscriber);

        return to_route('contact.message.index');
    }
}
