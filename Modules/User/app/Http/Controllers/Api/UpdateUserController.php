<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Modules\User\Http\Requests\UpdateUserRequest;
use Modules\User\Services\UserService;

final class UpdateUserController
{
    public function __invoke(UpdateUserRequest $request, User $user, UserService $users): JsonResponse
    {
        $updated = $users->update($user, $request->userData());

        return response()->json([
            'data' => [
                'id' => $updated->id,
                'name' => $updated->name,
                'email' => $updated->email,
            ],
        ]);
    }
}
