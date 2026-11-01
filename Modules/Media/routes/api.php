<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Media\Http\Controllers\Api\DestroyMediaController;
use Modules\Media\Http\Controllers\Api\IndexMediaController;
use Modules\Media\Http\Controllers\Api\ShowMediaController;
use Modules\Media\Http\Controllers\Api\StoreMediaController;
use Modules\Media\Http\Controllers\Api\UpdateMediaController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function (): void {
    Route::get('media', IndexMediaController::class)->name('media.index');
    Route::post('media', StoreMediaController::class)->name('media.store');
    Route::get('media/{media}', ShowMediaController::class)->name('media.show');
    Route::match(['put', 'patch'], 'media/{media}', UpdateMediaController::class)->name('media.update');
    Route::delete('media/{media}', DestroyMediaController::class)->name('media.destroy');
});
