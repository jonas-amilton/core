<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Modules\User\Contracts\UserRepository;

final class IndexUserController
{
    public function __invoke(Request $request, UserRepository $users): Response
    {
        $search = trim($request->string('search')->toString());

        return Inertia::render('User::Index', [
            'users' => $users->all($search !== '' ? $search : null)
                ->map(fn (User $user): array => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'created_at' => $user->created_at?->toIso8601String(),
                ])
                ->all(),
            'search' => $search,
        ]);
    }
}
