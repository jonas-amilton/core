<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Catalog\Http\Controllers\Admin\CreateProductController;
use Modules\Catalog\Http\Controllers\Admin\DestroyCategoryController;
use Modules\Catalog\Http\Controllers\Admin\DestroyProductController;
use Modules\Catalog\Http\Controllers\Admin\EditProductController;
use Modules\Catalog\Http\Controllers\Admin\IndexCategoryController;
use Modules\Catalog\Http\Controllers\Admin\IndexProductController;
use Modules\Catalog\Http\Controllers\Admin\StoreCategoryController;
use Modules\Catalog\Http\Controllers\Admin\StoreProductController;
use Modules\Catalog\Http\Controllers\Admin\UpdateCategoryController;
use Modules\Catalog\Http\Controllers\Admin\UpdateProductController;
use Modules\Catalog\Http\Controllers\Site\CategoryCatalogController;
use Modules\Catalog\Http\Controllers\Site\IndexCatalogController;
use Modules\Catalog\Http\Controllers\Site\ShowProductController;

Route::get('produtos', IndexCatalogController::class)->name('catalog.index');
Route::get('produtos/c/{category:slug}', CategoryCatalogController::class)->name('catalog.category');
Route::get('produto/{product:slug}', ShowProductController::class)->name('catalog.show');

Route::middleware(['auth', 'verified'])->prefix('painel/produtos')->group(function (): void {
    Route::get('/', IndexProductController::class)->name('catalog.product.index');
    Route::get('novo', CreateProductController::class)->name('catalog.product.create');
    Route::post('/', StoreProductController::class)->name('catalog.product.store');

    Route::get('categorias', IndexCategoryController::class)->name('catalog.category.index');
    Route::post('categorias', StoreCategoryController::class)->name('catalog.category.store');
    Route::patch('categorias/{category}', UpdateCategoryController::class)->name('catalog.category.update');
    Route::delete('categorias/{category}', DestroyCategoryController::class)->name('catalog.category.destroy');

    Route::get('{product}/editar', EditProductController::class)->name('catalog.product.edit');
    Route::patch('{product}', UpdateProductController::class)->name('catalog.product.update');
    Route::delete('{product}', DestroyProductController::class)->name('catalog.product.destroy');
});
