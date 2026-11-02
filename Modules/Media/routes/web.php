<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Media\Http\Controllers\Admin\CreateMediaController;
use Modules\Media\Http\Controllers\Admin\DestroyMediaController;
use Modules\Media\Http\Controllers\Admin\EditMediaController;
use Modules\Media\Http\Controllers\Admin\IndexMediaController;
use Modules\Media\Http\Controllers\Admin\PickerMediaController;
use Modules\Media\Http\Controllers\Admin\StoreMediaController;
use Modules\Media\Http\Controllers\Admin\UpdateMediaController;

Route::middleware(['auth', 'verified', 'can:access-panel'])->group(function (): void {
    Route::get('media', IndexMediaController::class)->name('media.index');
    Route::get('media/picker', PickerMediaController::class)->name('media.picker');
    Route::get('media/create', CreateMediaController::class)->name('media.create');
    Route::post('media', StoreMediaController::class)->name('media.store');
    Route::get('media/{media}/edit', EditMediaController::class)->name('media.edit');
    Route::match(['put', 'patch'], 'media/{media}', UpdateMediaController::class)->name('media.update');
    Route::delete('media/{media}', DestroyMediaController::class)->name('media.destroy');
});
