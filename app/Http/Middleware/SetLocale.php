<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Response;

/**
 * Persists the visitor's language in the session and applies it to the request.
 * The public site switches language with an Inertia reload (`?locale=en`), so no
 * URL prefix is needed and the admin stays in pt-BR.
 */
final class SetLocale
{
    public const SESSION_KEY = 'locale';

    /**
     * @var array<int, string>
     */
    public const SUPPORTED = ['pt_BR', 'en', 'es'];

    public function handle(Request $request, Closure $next): Response
    {
        $requested = $request->query('locale');

        if (is_string($requested) && in_array($requested, self::SUPPORTED, true)) {
            $request->session()->put(self::SESSION_KEY, $requested);
        }

        $locale = $request->session()->get(self::SESSION_KEY);

        if (! is_string($locale) || ! in_array($locale, self::SUPPORTED, true)) {
            $locale = (string) config('app.locale');
        }

        app()->setLocale($locale);
        Carbon::setLocale(str_replace('_', '-', $locale));

        return $next($request);
    }
}
