<?php

declare(strict_types=1);

namespace Modules\Settings\Http\Controllers\Site;

use Inertia\Inertia;
use Inertia\Response;
use Modules\Page\Services\SiteNavigation;
use Modules\Settings\Services\SettingsService;

final class CookiePolicyController
{
    public function __invoke(SettingsService $settings, SiteNavigation $navigation): Response
    {
        return Inertia::render('Site::CookiePolicy', [
            'cookieText' => $settings->get('cookie_text'),
            'navigation' => $navigation->items(),
        ]);
    }
}
