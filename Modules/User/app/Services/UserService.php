<?php

declare(strict_types=1);

namespace Modules\User\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Modules\User\Contracts\UserRepository;
use Modules\User\Data\UserData;

final class UserService
{
    public function __construct(private UserRepository $users) {}

    public function create(UserData $data): User
    {
        return $this->users->create(
            $data->name,
            $data->email,
            Hash::make((string) $data->password),
        );
    }

    public function update(User $user, UserData $data): User
    {
        return $this->users->update(
            $user,
            $data->name,
            $data->email,
            $data->password !== null ? Hash::make($data->password) : null,
        );
    }

    public function delete(User $user, User $actor): void
    {
        if ($user->is($actor)) {
            throw ValidationException::withMessages([
                'user' => 'Você não pode excluir a própria conta por aqui.',
            ]);
        }

        if ($this->users->count() <= 1) {
            throw ValidationException::withMessages([
                'user' => 'O último usuário do painel não pode ser excluído.',
            ]);
        }

        $this->users->delete($user);
    }
}
