<?php

declare(strict_types=1);

namespace Modules\User\Data;

use App\Enums\UserRole;

final readonly class UserData
{
    public function __construct(
        public string $name,
        public string $email,
        public UserRole $role = UserRole::Editor,
        public ?string $password = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        $password = $data['password'] ?? null;

        return new self(
            name: (string) $data['name'],
            email: (string) $data['email'],
            role: UserRole::from((string) ($data['role'] ?? UserRole::Editor->value)),
            password: is_string($password) && $password !== '' ? $password : null,
        );
    }
}
