<?php

declare(strict_types=1);

namespace Modules\User\Data;

final readonly class UserData
{
    public function __construct(
        public string $name,
        public string $email,
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
            password: is_string($password) && $password !== '' ? $password : null,
        );
    }
}
