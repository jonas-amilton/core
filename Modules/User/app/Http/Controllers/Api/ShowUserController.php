<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\JsonResponse;

final class ShowUserController
{
    public function __invoke(User $user): JsonResponse
    {
        return response()->json([
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at?->toIso8601String(),
            ],
        ]);
    }
}
