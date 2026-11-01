<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\User\Services\UserService;

final class DestroyUserController
{
    public function __invoke(Request $request, User $user, UserService $users): RedirectResponse
    {
        $actor = $request->user();

        abort_if($actor === null, 403);

        $users->delete($user, $actor);

        return to_route('user.index');
    }
}
