<?php

declare(strict_types=1);

namespace Modules\Contact\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Modules\Contact\Models\ContactMessage;
use Modules\Contact\Services\ContactService;

final class DestroyContactMessageController
{
    public function __invoke(ContactMessage $message, ContactService $contact): RedirectResponse
    {
        $contact->deleteMessage($message);

        return to_route('contact.message.index');
    }
}
