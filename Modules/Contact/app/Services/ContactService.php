<?php

declare(strict_types=1);

namespace Modules\Contact\Services;

use Illuminate\Database\Eloquent\Collection;
use Modules\Contact\Contracts\ContactRepository;
use Modules\Contact\Models\ContactMessage;
use Modules\Contact\Models\Subscriber;

final class ContactService
{
    public function __construct(private ContactRepository $contact) {}

    /**
     * @param  array{name: string, email: string, phone: string|null, message: string}  $data
     */
    public function storeMessage(array $data): ContactMessage
    {
        return $this->contact->createMessage($data);
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
