<?php

declare(strict_types=1);

namespace Modules\Contact\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Contact\Contracts\ContactRepository;
use Modules\Contact\Models\ContactMessage;
use Modules\Contact\Models\Subscriber;

final class EloquentContactRepository implements ContactRepository
{
    /**
     * @return Collection<int, ContactMessage>
     */
    public function messages(): Collection
    {
        return ContactMessage::query()->latest('id')->get();
    }

    /**
     * @param  array{name: string, email: string, phone: string|null, message: string}  $data
     */
    public function createMessage(array $data): ContactMessage
    {
        return ContactMessage::query()->create($data);
    }

    public function deleteMessage(ContactMessage $message): void
    {
        $message->delete();
    }

    /**
     * @return Collection<int, Subscriber>
     */
    public function subscribers(): Collection
    {
        return Subscriber::query()->latest('id')->get();
    }

    public function createSubscriber(string $email): Subscriber
    {
        return Subscriber::query()->firstOrCreate(['email' => $email]);
    }

    public function deleteSubscriber(Subscriber $subscriber): void
    {
        $subscriber->delete();
    }
}
