<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Quote\Http\Controllers\Admin\DestroyQuoteController;
use Modules\Quote\Http\Controllers\Admin\IndexQuoteController;
use Modules\Quote\Http\Controllers\Admin\UpdateQuoteController;
use Modules\Quote\Http\Controllers\Site\CartController;
use Modules\Quote\Http\Controllers\Site\DestroyCartItemController;
use Modules\Quote\Http\Controllers\Site\StoreCartItemController;
use Modules\Quote\Http\Controllers\Site\StoreQuoteController;
use Modules\Quote\Http\Controllers\Site\UpdateCartItemController;

Route::get('orcamento', CartController::class)->name('quote.cart');
Route::post('orcamento/itens', StoreCartItemController::class)
    ->middleware('throttle:20,1')
    ->name('quote.item.store');
Route::patch('orcamento/itens/{product}', UpdateCartItemController::class)
    ->whereNumber('product')
    ->name('quote.item.update');
Route::delete('orcamento/itens/{product}', DestroyCartItemController::class)
    ->whereNumber('product')
    ->name('quote.item.destroy');
Route::post('orcamento', StoreQuoteController::class)
    ->middleware('throttle:5,1')
    ->name('quote.store');

Route::middleware(['auth', 'verified', 'can:access-panel'])->prefix('painel/orcamentos')->group(function (): void {
    Route::get('/', IndexQuoteController::class)->name('quote.index');
    Route::patch('{quote}', UpdateQuoteController::class)->name('quote.update');
    Route::delete('{quote}', DestroyQuoteController::class)->name('quote.destroy');
});
