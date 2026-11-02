<?php

declare(strict_types=1);

namespace Modules\User\Http\Requests;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Modules\User\Data\UserData;

final class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', Rule::enum(UserRole::class)],
            'password' => ['required', 'string', 'confirmed', Password::defaults()],
        ];
    }

    public function userData(): UserData
    {
        return UserData::fromArray([
            'name' => (string) $this->input('name'),
            'email' => (string) $this->input('email'),
            'role' => (string) $this->input('role'),
            'password' => $this->input('password'),
        ]);
    }
}
