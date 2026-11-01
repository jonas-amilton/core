<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Modules\User\Http\Requests\UpdateUserRequest;
use Modules\User\Services\UserService;

final class UpdateUserController
{
    public function __invoke(UpdateUserRequest $request, User $user, UserService $users): RedirectResponse
    {
        $users->update($user, $request->userData());

        return back();
    }
}
