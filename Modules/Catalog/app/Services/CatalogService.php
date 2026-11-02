<?php

declare(strict_types=1);

namespace Modules\Catalog\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Modules\Catalog\Contracts\CatalogRepository;
use Modules\Catalog\Data\CategoryData;
use Modules\Catalog\Data\ProductData;
use Modules\Catalog\Models\Category;
use Modules\Catalog\Models\Product;

final class CatalogService
{
    public function __construct(private CatalogRepository $catalog) {}

    /**
     * @return Collection<int, Category>
     */
    public function categories(): Collection
    {
        return $this->catalog->categories();
    }

    public function category(int $id): ?Category
    {
        return $this->catalog->findCategory($id);
    }

    public function createCategory(CategoryData $data): Category
    {
        $slug = $this->resolveCategorySlug($data->slug ?? $data->name, null);

        return $this->catalog->createCategory($data, $slug);
    }

    public function updateCategory(Category $category, CategoryData $data): Category
    {
        $slug = $data->slug !== null && $data->slug !== $category->slug
            ? $this->resolveCategorySlug($data->slug, $category)
            : $category->slug;

        return $this->catalog->updateCategory($category, $data, $slug);
    }

    public function deleteCategory(Category $category): void
    {
        $this->catalog->deleteCategory($category);
    }

    /**
     * Flat list with indented labels for the admin selects.
     *
     * @return array<int, array{id: int, label: string}>
     */
    public function categoryOptions(): array
    {
        $groups = $this->catalog->categories()->groupBy('parent_id');

        $options = [];
        $walk = function (?int $parentId, int $depth) use (&$walk, $groups, &$options): void {
            foreach ($groups->get($parentId, []) as $category) {
                /** @var Category $category */
                $options[] = [
                    'id' => $category->id,
                    'label' => str_repeat('— ', $depth).$category->name,
                ];
                $walk($category->id, $depth + 1);
            }
        };

        $walk(null, 0);

        return $options;
    }

    /**
     * @return array<int, int>
     */
    public function categoryIdsWithDescendants(Category $category): array
    {
        $categories = $this->catalog->categories();
        $ids = [$category->id];

        $walk = function (int $parentId) use (&$walk, $categories, &$ids): void {
            foreach ($categories->where('parent_id', $parentId) as $child) {
                /** @var Category $child */
                $ids[] = $child->id;
                $walk($child->id);
            }
        };

        $walk($category->id);

        return $ids;
    }

    /**
     * @return LengthAwarePaginator<int, Product>
     */
    public function products(
        ?string $search,
        ?int $categoryId,
        int $perPage = 20,
        string $sort = 'default',
        ?float $minPrice = null,
        ?float $maxPrice = null,
    ): LengthAwarePaginator {
        return $this->catalog->products($search, $categoryId, $perPage, $sort, $minPrice, $maxPrice);
    }

    /**
     * @return LengthAwarePaginator<int, Product>
     */
    public function productsOfCategory(
        Category $category,
        int $perPage = 12,
        string $sort = 'default',
        ?float $minPrice = null,
        ?float $maxPrice = null,
    ): LengthAwarePaginator {
        return $this->catalog->productsOfCategories(
            $this->categoryIdsWithDescendants($category),
            $perPage,
            $sort,
            $minPrice,
            $maxPrice,
        );
    }

    /**
     * @return array<int, array{id: int, name: string}>
     */
    public function productOptions(): array
    {
        return $this->catalog->allProducts()
            ->map(fn (Product $product): array => ['id' => $product->id, 'name' => $product->name])
            ->values()
            ->all();
    }

    /**
     * @return Collection<int, Product>
     */
    public function featured(int $limit): Collection
    {
        return $this->catalog->featured($limit);
    }

    public function productBySlug(string $slug): ?Product
    {
        return $this->catalog->findProductBySlug($slug);
    }

    public function createProduct(ProductData $data): Product
    {
        $slug = $this->resolveProductSlug($data->slug ?? $data->name, null);

        return $this->catalog->createProduct($data, $slug);
    }

    public function updateProduct(Product $product, ProductData $data): Product
    {
        $slug = $data->slug !== null && $data->slug !== $product->slug
            ? $this->resolveProductSlug($data->slug, $product)
            : $product->slug;

        return $this->catalog->updateProduct($product, $data, $slug);
    }

    public function deleteProduct(Product $product): void
    {
        $this->catalog->deleteProduct($product);
    }

    private function resolveCategorySlug(string $base, ?Category $ignore): string
    {
        return $this->uniqueSlug(
            Str::slug($base) ?: 'sem-categoria',
            fn (string $slug): bool => $this->catalog->categorySlugExists($slug, $ignore),
        );
    }

    private function resolveProductSlug(string $base, ?Product $ignore): string
    {
        return $this->uniqueSlug(
            Str::slug($base) ?: 'sem-produto',
            fn (string $slug): bool => $this->catalog->productSlugExists($slug, $ignore),
        );
    }

    /**
     * @param  callable(string): bool  $exists
     */
    private function uniqueSlug(string $base, callable $exists): string
    {
        $candidate = $base;
        $suffix = 2;

        while ($exists($candidate)) {
            $candidate = "{$base}-{$suffix}";
            $suffix++;
        }

        return $candidate;
    }
}
