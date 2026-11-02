<?php

declare(strict_types=1);

namespace Modules\User\Repositories;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\User\Contracts\UserRepository;

final class EloquentUserRepository implements UserRepository
{
    /**
     * @return Collection<int, User>
     */
    public function all(?string $search = null): Collection
    {
        return User::query()
            ->when($search !== null && $search !== '', fn (Builder $query): Builder => $query
                ->where(fn (Builder $inner): Builder => $inner
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")))
            ->orderBy('name')
            ->get();
    }

    public function find(int $id): ?User
    {
        return User::query()->find($id);
    }

    public function create(string $name, string $email, UserRole $role, string $passwordHash): User
    {
        return User::query()->create([
            'name' => $name,
            'email' => $email,
            'role' => $role,
            'password' => $passwordHash,
        ]);
    }

    public function update(User $user, string $name, string $email, UserRole $role, ?string $passwordHash): User
    {
        $user->update(array_filter([
            'name' => $name,
            'email' => $email,
            'role' => $role,
            'password' => $passwordHash,
        ], fn (mixed $value): bool => $value !== null));

        return $user->refresh();
    }

    public function delete(User $user): void
    {
        $user->delete();
    }

    public function count(): int
    {
        return User::query()->count();
    }
}
