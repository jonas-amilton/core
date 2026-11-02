<?php

declare(strict_types=1);

namespace Modules\Catalog\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Catalog\Data\CategoryData;
use Modules\Catalog\Data\ProductData;
use Modules\Catalog\Models\Category;
use Modules\Catalog\Models\Product;

interface CatalogRepository
{
    /**
     * @return Collection<int, Category>
     */
    public function categories(): Collection;

    public function findCategory(int $id): ?Category;

    public function createCategory(CategoryData $data, string $slug): Category;

    public function updateCategory(Category $category, CategoryData $data, string $slug): Category;

    public function deleteCategory(Category $category): void;

    public function categorySlugExists(string $slug, ?Category $ignore = null): bool;

    /**
     * @return LengthAwarePaginator<int, Product>
     */
    public function products(?string $search, ?int $categoryId, int $perPage = 20): LengthAwarePaginator;

    /**
     * @param  array<int, int>  $categoryIds
     */
    /**
     * @param  array<int, int>  $categoryIds
     * @return LengthAwarePaginator<int, Product>
     */
    public function productsOfCategories(array $categoryIds, int $perPage = 12): LengthAwarePaginator;

    /**
     * @return Collection<int, Product>
     */
    public function allProducts(): Collection;

    /**
     * @return Collection<int, Product>
     */
    public function featured(int $limit): Collection;

    public function findProduct(int $id): ?Product;

    public function findProductBySlug(string $slug): ?Product;

    public function createProduct(ProductData $data, string $slug): Product;

    public function updateProduct(Product $product, ProductData $data, string $slug): Product;

    public function deleteProduct(Product $product): void;

    public function productSlugExists(string $slug, ?Product $ignore = null): bool;
}
