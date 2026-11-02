<?php

declare(strict_types=1);

namespace Modules\Contact\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Modules\Contact\Models\ContactMessage;
use Modules\Contact\Models\Subscriber;

interface ContactRepository
{
    /**
     * @return Collection<int, ContactMessage>
     */
    public function messages(): Collection;

    /**
     * @param  array{name: string, email: string, phone: string|null, message: string}  $data
     */
    public function createMessage(array $data): ContactMessage;

    public function deleteMessage(ContactMessage $message): void;

    /**
     * @return Collection<int, Subscriber>
     */
    public function subscribers(): Collection;

    public function createSubscriber(string $email): Subscriber;

    public function deleteSubscriber(Subscriber $subscriber): void;
}
