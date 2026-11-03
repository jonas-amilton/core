<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Laravel\Fortify\Contracts\FailedPasswordResetLinkRequestResponse as FailedPasswordResetLinkRequestResponseContract;

/**
 * Fortify's default failure response returns a "user not found" error, which
 * lets anyone test whether an address has an account. Answer both outcomes
 * with the generic success message instead; mail is still only sent when the
 * address exists.
 */
final class FailedPasswordResetLinkRequestResponse implements FailedPasswordResetLinkRequestResponseContract
{
    public function toResponse($request)
    {
        return $request->wantsJson()
            ? new JsonResponse(['message' => trans('passwords.sent')], 200)
            : back()->with('status', trans('passwords.sent'));
    }
}
