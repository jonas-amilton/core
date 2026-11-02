<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Contact\Http\Controllers\Admin\DestroyContactMessageController;
use Modules\Contact\Http\Controllers\Admin\DestroySubscriberController;
use Modules\Contact\Http\Controllers\Admin\ExportSubscribersController;
use Modules\Contact\Http\Controllers\Admin\IndexContactController;
use Modules\Contact\Http\Controllers\Site\StoreCareerMessageController;
use Modules\Contact\Http\Controllers\Site\StoreContactMessageController;
use Modules\Contact\Http\Controllers\Site\StoreSubscriberController;

Route::post('contato', StoreContactMessageController::class)
    ->middleware('throttle:5,1')
    ->name('contact.store');

Route::post('trabalhe-conosco', StoreCareerMessageController::class)
    ->middleware('throttle:5,1')
    ->name('contact.career.store');

Route::post('newsletter', StoreSubscriberController::class)
    ->middleware('throttle:5,1')
    ->name('newsletter.store');

Route::middleware(['auth', 'verified', 'can:access-panel'])->prefix('painel/mensagens')->group(function (): void {
    Route::get('/', IndexContactController::class)->name('contact.message.index');
    Route::delete('contato/{message}', DestroyContactMessageController::class)->name('contact.message.destroy');
    Route::delete('inscrito/{subscriber}', DestroySubscriberController::class)->name('contact.subscriber.destroy');
    Route::get('inscritos.csv', ExportSubscribersController::class)->name('contact.subscriber.export');
});
