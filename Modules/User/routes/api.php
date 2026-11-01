<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\Api\DestroyUserController;
use Modules\User\Http\Controllers\Api\IndexUserController;
use Modules\User\Http\Controllers\Api\ShowUserController;
use Modules\User\Http\Controllers\Api\StoreUserController;
use Modules\User\Http\Controllers\Api\UpdateUserController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function (): void {
    Route::get('users', IndexUserController::class)->name('user.index');
    Route::post('users', StoreUserController::class)->name('user.store');
    Route::get('users/{user}', ShowUserController::class)->name('user.show');
    Route::match(['put', 'patch'], 'users/{user}', UpdateUserController::class)->name('user.update');
    Route::delete('users/{user}', DestroyUserController::class)->name('user.destroy');
});
