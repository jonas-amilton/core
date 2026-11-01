<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Page\Http\Controllers\Api\DestroyPageController;
use Modules\Page\Http\Controllers\Api\IndexPageController;
use Modules\Page\Http\Controllers\Api\ShowPageController;
use Modules\Page\Http\Controllers\Api\StorePageController;
use Modules\Page\Http\Controllers\Api\UpdatePageController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function (): void {
    Route::get('pages', IndexPageController::class)->name('page.index');
    Route::post('pages', StorePageController::class)->name('page.store');
    Route::get('pages/{page}', ShowPageController::class)->name('page.show');
    Route::match(['put', 'patch'], 'pages/{page}', UpdatePageController::class)->name('page.update');
    Route::delete('pages/{page}', DestroyPageController::class)->name('page.destroy');
});
