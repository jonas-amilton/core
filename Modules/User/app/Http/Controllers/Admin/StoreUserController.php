<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Modules\User\Http\Requests\StoreUserRequest;
use Modules\User\Services\UserService;

final class StoreUserController
{
    public function __invoke(StoreUserRequest $request, UserService $users): RedirectResponse
    {
        $users->create($request->userData());

        return to_route('user.index');
    }
}
