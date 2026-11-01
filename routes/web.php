<?php

declare(strict_types=1);

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Modules\Page\Http\Controllers\Site\HomeController;

// Landing do CoreCMS: apresentação do produto + entrada para o painel.
Route::inertia('admin', 'Welcome')->name('admin');

// Site público: a home é a página inicial do site e também responde em /home.
Route::get('/', HomeController::class)->name('home');
Route::get('home', HomeController::class)->name('site.home');

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::get('dashboard', DashboardController::class)->name('dashboard');
});

require __DIR__.'/settings.php';
