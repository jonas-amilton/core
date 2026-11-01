<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Modules\User\Http\Requests\StoreUserRequest;
use Modules\User\Services\UserService;

final class StoreUserController
{
    public function __invoke(StoreUserRequest $request, UserService $users): JsonResponse
    {
        $user = $users->create($request->userData());

        return response()->json([
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ], 201);
    }
}
