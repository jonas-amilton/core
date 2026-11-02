<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Store\Http\Controllers\Admin\DestroyStoreController;
use Modules\Store\Http\Controllers\Admin\IndexStoreController as AdminIndexStoreController;
use Modules\Store\Http\Controllers\Admin\StoreStoreController;
use Modules\Store\Http\Controllers\Admin\UpdateStoreController;
use Modules\Store\Http\Controllers\Site\IndexStoreController;

Route::get('onde-comprar', IndexStoreController::class)->name('stores.public');

Route::middleware(['auth', 'verified'])->prefix('painel/lojas')->group(function (): void {
    Route::get('/', AdminIndexStoreController::class)->name('stores.index');
    Route::post('/', StoreStoreController::class)->name('stores.store');
    Route::patch('{store}', UpdateStoreController::class)->name('stores.update');
    Route::delete('{store}', DestroyStoreController::class)->name('stores.destroy');
});
