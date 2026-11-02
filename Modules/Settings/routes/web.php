<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Settings\Http\Controllers\Admin\IndexSettingsController;
use Modules\Settings\Http\Controllers\Admin\UpdateSettingsController;

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::get('painel/configuracoes', IndexSettingsController::class)->name('settings.index');
    Route::put('painel/configuracoes', UpdateSettingsController::class)->name('settings.update');
});
