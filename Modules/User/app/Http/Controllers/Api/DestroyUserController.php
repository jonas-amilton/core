<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\User\Services\UserService;

final class DestroyUserController
{
    public function __invoke(Request $request, User $user, UserService $users): Response
    {
        $actor = $request->user();

        abort_if($actor === null, 403);

        $users->delete($user, $actor);

        return response()->noContent();
    }
}
