<?php

declare(strict_types=1);

namespace Modules\Page\Support;

final class ReservedSlugs
{
    /**
     * Slugs that belong to the application shell and can never be used by a page.
     *
     * @var array<int, string>
     */
    public const LIST = [
        '404',
        'admin',
        'api',
        'dashboard',
        'home',
        'login',
        'logout',
        'media',
        'pages',
        'register',
        'settings',
        'storage',
        'users',
    ];

    public static function contains(string $slug): bool
    {
        return in_array($slug, self::LIST, true);
    }
}
