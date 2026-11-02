<?php

declare(strict_types=1);

namespace Modules\User\Contracts;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserRepository
{
    /**
     * @return Collection<int, User>
     */
    public function all(?string $search = null): Collection;

    public function find(int $id): ?User;

    public function create(string $name, string $email, UserRole $role, string $passwordHash): User;

    public function update(User $user, string $name, string $email, UserRole $role, ?string $passwordHash): User;

    public function delete(User $user): void;

    public function count(): int;
}
