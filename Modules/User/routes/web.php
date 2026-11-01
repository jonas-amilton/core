<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\Admin\CreateUserController;
use Modules\User\Http\Controllers\Admin\DestroyUserController;
use Modules\User\Http\Controllers\Admin\EditUserController;
use Modules\User\Http\Controllers\Admin\IndexUserController;
use Modules\User\Http\Controllers\Admin\StoreUserController;
use Modules\User\Http\Controllers\Admin\UpdateUserController;

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::get('users', IndexUserController::class)->name('user.index');
    Route::get('users/create', CreateUserController::class)->name('user.create');
    Route::post('users', StoreUserController::class)->name('user.store');
    Route::get('users/{user}/edit', EditUserController::class)->name('user.edit');
    Route::match(['put', 'patch'], 'users/{user}', UpdateUserController::class)->name('user.update');
    Route::delete('users/{user}', DestroyUserController::class)->name('user.destroy');
});
