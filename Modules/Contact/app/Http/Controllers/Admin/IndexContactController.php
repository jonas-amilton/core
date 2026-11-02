<?php

declare(strict_types=1);

namespace Modules\Contact\Http\Controllers\Admin;

use Inertia\Inertia;
use Inertia\Response;
use Modules\Contact\Models\ContactMessage;
use Modules\Contact\Models\Subscriber;
use Modules\Contact\Services\ContactService;

final class IndexContactController
{
    public function __invoke(ContactService $contact): Response
    {
        return Inertia::render('Contact::Index', [
            'messages' => $contact->messages()
                ->map(fn (ContactMessage $message): array => $message->toAdminArray())
                ->all(),
            'subscribers' => $contact->subscribers()
                ->map(fn (Subscriber $subscriber): array => $subscriber->toAdminArray())
                ->all(),
        ]);
    }
}
