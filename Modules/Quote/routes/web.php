<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Quote\Http\Controllers\Admin\DestroyCouponController;
use Modules\Quote\Http\Controllers\Admin\DestroyQuoteController;
use Modules\Quote\Http\Controllers\Admin\IndexCouponController;
use Modules\Quote\Http\Controllers\Admin\IndexQuoteController;
use Modules\Quote\Http\Controllers\Admin\StoreCouponController;
use Modules\Quote\Http\Controllers\Admin\ToggleCouponController;
use Modules\Quote\Http\Controllers\Admin\UpdateQuoteController;
use Modules\Quote\Http\Controllers\Site\ApplyCouponController;
use Modules\Quote\Http\Controllers\Site\CartController;
use Modules\Quote\Http\Controllers\Site\DestroyCartItemController;
use Modules\Quote\Http\Controllers\Site\RemoveCouponController;
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
Route::post('orcamento/cupom', ApplyCouponController::class)
    ->middleware('throttle:10,1')
    ->name('quote.coupon.store');
Route::delete('orcamento/cupom', RemoveCouponController::class)
    ->name('quote.coupon.destroy');
Route::post('orcamento', StoreQuoteController::class)
    ->middleware('throttle:5,1')
    ->name('quote.store');

Route::middleware(['auth', 'verified', 'can:access-panel'])->prefix('painel/orcamentos')->group(function (): void {
    Route::get('/', IndexQuoteController::class)->name('quote.index');

    Route::get('cupons', IndexCouponController::class)->name('quote.coupon.index');
    Route::post('cupons', StoreCouponController::class)->name('quote.coupon.store.admin');
    Route::patch('cupons/{coupon}', ToggleCouponController::class)->name('quote.coupon.toggle');
    Route::delete('cupons/{coupon}', DestroyCouponController::class)->name('quote.coupon.destroy.admin');
    Route::patch('{quote}', UpdateQuoteController::class)->name('quote.update');
    Route::delete('{quote}', DestroyQuoteController::class)->name('quote.destroy');
});
