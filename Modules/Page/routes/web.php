<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Page\Http\Controllers\Admin\CreatePageController;
use Modules\Page\Http\Controllers\Admin\DestroyPageController;
use Modules\Page\Http\Controllers\Admin\EditPageController;
use Modules\Page\Http\Controllers\Admin\IndexPageController;
use Modules\Page\Http\Controllers\Admin\PreviewPageController;
use Modules\Page\Http\Controllers\Admin\PublishPageController;
use Modules\Page\Http\Controllers\Admin\RestorePageRevisionController;
use Modules\Page\Http\Controllers\Admin\StorePageController;
use Modules\Page\Http\Controllers\Admin\UnpublishPageController;
use Modules\Page\Http\Controllers\Admin\UpdatePageController;

Route::middleware(['auth', 'verified', 'can:access-panel'])->group(function (): void {
    Route::get('pages', IndexPageController::class)->name('page.index');
    Route::get('pages/create', CreatePageController::class)->name('page.create');
    Route::post('pages', StorePageController::class)->name('page.store');
    Route::get('pages/{page}/edit', EditPageController::class)->name('page.edit');
    Route::get('pages/{page}/preview', PreviewPageController::class)->name('page.preview');
    Route::match(['put', 'patch'], 'pages/{page}', UpdatePageController::class)->name('page.update');
    Route::delete('pages/{page}', DestroyPageController::class)->name('page.destroy');
    Route::post('pages/{page}/publish', PublishPageController::class)->name('page.publish');
    Route::post('pages/{page}/unpublish', UnpublishPageController::class)->name('page.unpublish');
    Route::post('pages/{page}/revisions/{revision}/restore', RestorePageRevisionController::class)
        ->name('page.revisions.restore');
});
