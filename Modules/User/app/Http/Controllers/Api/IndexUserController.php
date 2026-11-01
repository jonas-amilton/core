<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Modules\User\Contracts\UserRepository;

final class IndexUserController
{
    public function __invoke(UserRepository $users): JsonResponse
    {
        return response()->json([
            'data' => $users->all()
                ->map(fn (User $user): array => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'created_at' => $user->created_at?->toIso8601String(),
                ])
                ->all(),
        ]);
    }
}
