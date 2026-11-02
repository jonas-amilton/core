<?php

declare(strict_types=1);

namespace Modules\Settings\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Modules\Settings\Http\Requests\UpdateSettingsRequest;
use Modules\Settings\Services\SettingsService;

final class UpdateSettingsController
{
    public function __invoke(UpdateSettingsRequest $request, SettingsService $settings): RedirectResponse
    {
        $settings->update($request->settings());

        return to_route('settings.index')->with('success', 'Configurações salvas.');
    }
}
