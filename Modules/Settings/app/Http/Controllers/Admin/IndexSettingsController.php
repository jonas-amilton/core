<?php

declare(strict_types=1);

namespace Modules\Settings\Http\Controllers\Admin;

use Inertia\Inertia;
use Inertia\Response;
use Modules\Settings\Data\SettingsData;
use Modules\Settings\Services\SettingsService;

final class IndexSettingsController
{
    public function __invoke(SettingsService $settings): Response
    {
        return Inertia::render('Settings::Index', [
            'values' => $settings->all(),
            'fields' => SettingsData::fields(),
        ]);
    }
}
