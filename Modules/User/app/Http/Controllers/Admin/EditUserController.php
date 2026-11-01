<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class EditUserController
{
    public function __invoke(Request $request, User $user): Response
    {
        return Inertia::render('User::Edit', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at?->toIso8601String(),
            ],
            'isSelf' => $request->user()?->is($user) ?? false,
        ]);
    }
}
