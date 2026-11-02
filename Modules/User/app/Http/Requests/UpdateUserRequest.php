<?php

declare(strict_types=1);

namespace Modules\User\Http\Requests;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Modules\User\Data\UserData;

final class UpdateUserRequest extends FormRequest
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
        $user = $this->route('user');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user instanceof User ? $user->id : null),
            ],
            'role' => ['required', Rule::enum(UserRole::class)],
            'password' => ['nullable', 'string', 'confirmed', Password::defaults()],
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
