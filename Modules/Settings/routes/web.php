<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Settings\Http\Controllers\Admin\IndexSettingsController;
use Modules\Settings\Http\Controllers\Admin\UpdateSettingsController;
use Modules\Settings\Http\Controllers\Site\CookiePolicyController;

Route::get('politica-de-cookies', CookiePolicyController::class)
    ->name('settings.cookie-policy');

Route::middleware(['auth', 'verified', 'can:manage-settings'])->group(function (): void {
    Route::get('painel/configuracoes', IndexSettingsController::class)->name('settings.index');
    Route::put('painel/configuracoes', UpdateSettingsController::class)->name('settings.update');
});
