<?php

declare(strict_types=1);

namespace Modules\Contact\Services;

use App\Mail\ContactMessageMail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Mail;
use Modules\Contact\Contracts\ContactRepository;
use Modules\Contact\Models\ContactMessage;
use Modules\Contact\Models\Subscriber;
use Modules\Settings\Services\SettingsService;
use Throwable;

final class ContactService
{
    public function __construct(
        private ContactRepository $contact,
        private SettingsService $settings,
    ) {}

    /**
     * @param  array{name: string, email: string, phone: string|null, message: string, kind: string}  $data
     */
    public function storeMessage(array $data): ContactMessage
    {
        $message = $this->contact->createMessage($data);

        $this->notify($message);

        return $message;
    }

    private function notify(ContactMessage $message): void
    {
        $adminEmail = $this->settings->get('email');

        if ($adminEmail === '') {
            return;
        }

        try {
            Mail::to($adminEmail)->send(new ContactMessageMail($message));
        } catch (Throwable $exception) {
            report($exception);
        }
    }

    public function subscribe(string $email): Subscriber
    {
        return $this->contact->createSubscriber(mb_strtolower(trim($email)));
    }

    /**
     * @return Collection<int, ContactMessage>
     */
    public function messages(): Collection
    {
        return $this->contact->messages();
    }

    /**
     * @return Collection<int, Subscriber>
     */
    public function subscribers(): Collection
    {
        return $this->contact->subscribers();
    }

    public function deleteMessage(ContactMessage $message): void
    {
        $this->contact->deleteMessage($message);
    }

    public function deleteSubscriber(Subscriber $subscriber): void
    {
        $this->contact->deleteSubscriber($subscriber);
    }
}
