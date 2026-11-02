<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Http\RedirectResponse;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

final class LoginResponse implements LoginResponseContract
{
    public function toResponse($request): RedirectResponse
    {
        $user = $request->user();

        $target = $user !== null && $user->canUsePanel()
            ? '/dashboard'
            : '/minha-conta';

        return redirect()->intended($target);
    }
}
