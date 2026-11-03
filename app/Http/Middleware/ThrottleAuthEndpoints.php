<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

/**
 * Fortify only throttles the routes it has limiters for (login, 2FA,
 * passkeys). These account-creation and password endpoints need the same
 * request-level guard.
 */
final class ThrottleAuthEndpoints
{
    /**
     * @var array<string, array{0: string, 1: int}>
     */
    private const LIMITS = [
        'register.store' => ['auth-register', 6],
        'password.email' => ['auth-password-email', 6],
        'password.update' => ['auth-password-reset', 6],
        'password.confirm.store' => ['auth-password-confirm', 6],
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $limit = self::LIMITS[$request->route()?->getName() ?? ''] ?? null;

        if ($limit !== null) {
            [$prefix, $perMinute] = $limit;
            $key = $prefix.':'.$request->ip();

            if ($request->route()?->getName() === 'password.confirm.store') {
                $user = $request->user();
                $key = $prefix.':'.($user === null ? 'guest' : (string) $user->getAuthIdentifier()).':'.$request->ip();
            }

            if (RateLimiter::tooManyAttempts($key, $perMinute)) {
                throw new ThrottleRequestsException(
                    'Too Many Attempts.',
                    headers: ['Retry-After' => RateLimiter::availableIn($key)],
                );
            }

            RateLimiter::hit($key, 60);
        }

        return $next($request);
    }
}
