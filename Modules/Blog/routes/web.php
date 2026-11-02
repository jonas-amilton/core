<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Blog\Http\Controllers\Admin\CreatePostController;
use Modules\Blog\Http\Controllers\Admin\DestroyCategoryController;
use Modules\Blog\Http\Controllers\Admin\DestroyPostController;
use Modules\Blog\Http\Controllers\Admin\EditPostController;
use Modules\Blog\Http\Controllers\Admin\IndexCategoryController;
use Modules\Blog\Http\Controllers\Admin\IndexPostController;
use Modules\Blog\Http\Controllers\Admin\StoreCategoryController;
use Modules\Blog\Http\Controllers\Admin\StorePostController;
use Modules\Blog\Http\Controllers\Admin\UpdateCategoryController;
use Modules\Blog\Http\Controllers\Admin\UpdatePostController;
use Modules\Blog\Http\Controllers\Site\CategoryBlogController;
use Modules\Blog\Http\Controllers\Site\IndexBlogController;
use Modules\Blog\Http\Controllers\Site\ShowBlogController;

Route::get('blog', IndexBlogController::class)->name('blog.feed');
Route::get('blog/categoria/{category:slug}', CategoryBlogController::class)->name('blog.category.show');
Route::get('blog/{post:slug}', ShowBlogController::class)->name('blog.show');

Route::middleware(['auth', 'verified'])->prefix('painel/blog')->group(function (): void {
    Route::get('/', IndexPostController::class)->name('blog.index');
    Route::get('novo', CreatePostController::class)->name('blog.create');
    Route::post('/', StorePostController::class)->name('blog.store');

    Route::get('categorias', IndexCategoryController::class)->name('blog.category.index');
    Route::post('categorias', StoreCategoryController::class)->name('blog.category.store');
    Route::patch('categorias/{category}', UpdateCategoryController::class)->name('blog.category.update');
    Route::delete('categorias/{category}', DestroyCategoryController::class)->name('blog.category.destroy');

    Route::get('{post}/editar', EditPostController::class)->name('blog.edit');
    Route::patch('{post}', UpdatePostController::class)->name('blog.update');
    Route::delete('{post}', DestroyPostController::class)->name('blog.destroy');
});
