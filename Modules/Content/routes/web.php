<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Content\Http\Controllers\Admin\DestroyClientController;
use Modules\Content\Http\Controllers\Admin\DestroyFaqController;
use Modules\Content\Http\Controllers\Admin\DestroyTestimonialController;
use Modules\Content\Http\Controllers\Admin\IndexClientController;
use Modules\Content\Http\Controllers\Admin\IndexFaqController;
use Modules\Content\Http\Controllers\Admin\IndexTestimonialController;
use Modules\Content\Http\Controllers\Admin\StoreClientController;
use Modules\Content\Http\Controllers\Admin\StoreFaqController;
use Modules\Content\Http\Controllers\Admin\StoreTestimonialController;
use Modules\Content\Http\Controllers\Admin\UpdateClientController;
use Modules\Content\Http\Controllers\Admin\UpdateFaqController;
use Modules\Content\Http\Controllers\Admin\UpdateTestimonialController;

Route::middleware(['auth', 'verified'])->prefix('painel')->group(function (): void {
    Route::get('depoimentos', IndexTestimonialController::class)->name('testimonial.index');
    Route::post('depoimentos', StoreTestimonialController::class)->name('testimonial.store');
    Route::patch('depoimentos/{testimonial}', UpdateTestimonialController::class)->name('testimonial.update');
    Route::delete('depoimentos/{testimonial}', DestroyTestimonialController::class)->name('testimonial.destroy');

    Route::get('faq', IndexFaqController::class)->name('faq.index');
    Route::post('faq', StoreFaqController::class)->name('faq.store');
    Route::patch('faq/{faq}', UpdateFaqController::class)->name('faq.update');
    Route::delete('faq/{faq}', DestroyFaqController::class)->name('faq.destroy');

    Route::get('clientes', IndexClientController::class)->name('client.index');
    Route::post('clientes', StoreClientController::class)->name('client.store');
    Route::patch('clientes/{client}', UpdateClientController::class)->name('client.update');
    Route::delete('clientes/{client}', DestroyClientController::class)->name('client.destroy');
});
