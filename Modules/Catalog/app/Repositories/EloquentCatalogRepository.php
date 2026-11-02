<?php

declare(strict_types=1);

namespace Modules\Catalog\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Catalog\Contracts\CatalogRepository;
use Modules\Catalog\Data\CategoryData;
use Modules\Catalog\Data\ProductData;
use Modules\Catalog\Models\Category;
use Modules\Catalog\Models\Product;

final class EloquentCatalogRepository implements CatalogRepository
{
    /**
     * @return Collection<int, Category>
     */
    public function categories(): Collection
    {
        return Category::query()
            ->withCount('products')
            ->orderBy('position')
            ->orderBy('name')
            ->get();
    }

    public function findCategory(int $id): ?Category
    {
        return Category::query()->find($id);
    }

    public function createCategory(CategoryData $data, string $slug): Category
    {
        return Category::query()->create([
            'parent_id' => $data->parentId,
            'name' => $data->name,
            'slug' => $slug,
            'position' => $data->position,
            'translations' => $data->translations !== [] ? $data->translations : null,
        ]);
    }

    public function updateCategory(Category $category, CategoryData $data, string $slug): Category
    {
        $category->update([
            'parent_id' => $data->parentId === $category->id ? null : $data->parentId,
            'name' => $data->name,
            'slug' => $slug,
            'position' => $data->position,
            'translations' => $data->translations !== [] ? $data->translations : null,
        ]);

        return $category->refresh();
    }

    public function deleteCategory(Category $category): void
    {
        $category->delete();
    }

    public function categorySlugExists(string $slug, ?Category $ignore = null): bool
    {
        return Category::query()
            ->where('slug', $slug)
            ->when($ignore, fn (Builder $query): Builder => $query->whereKeyNot($ignore->getKey()))
            ->exists();
    }

    /**
     * @return LengthAwarePaginator<int, Product>
     */
    public function products(?string $search, ?int $categoryId, int $perPage = 20): LengthAwarePaginator
    {
        return Product::query()
            ->with(['media', 'categories'])
            ->when($search !== null && $search !== '', fn (Builder $query): Builder => $query
                ->where(fn (Builder $inner): Builder => $inner
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")))
            ->when($categoryId !== null, fn (Builder $query): Builder => $query
                ->whereHas('categories', fn (Builder $inner): Builder => $inner->whereKey($categoryId)))
            ->orderBy('position')
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * @param  array<int, int>  $categoryIds
     * @return LengthAwarePaginator<int, Product>
     */
    public function productsOfCategories(array $categoryIds, int $perPage = 12): LengthAwarePaginator
    {
        return Product::query()
            ->with(['media', 'categories'])
            ->when($categoryIds !== [], fn (Builder $query): Builder => $query
                ->whereHas('categories', fn (Builder $inner): Builder => $inner->whereIn('catalog_categories.id', $categoryIds)))
            ->orderBy('position')
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * @return Collection<int, Product>
     */
    public function allProducts(): Collection
    {
        return Product::query()->orderBy('name')->get();
    }

    /**
     * @return Collection<int, Product>
     */
    public function featured(int $limit): Collection
    {
        return Product::query()
            ->with(['media', 'categories'])
            ->orderBy('position')
            ->orderBy('name')
            ->limit(max(1, min(12, $limit)))
            ->get();
    }

    public function findProduct(int $id): ?Product
    {
        return Product::query()
            ->with(['media', 'categories', 'related.media'])
            ->find($id);
    }

    public function findProductBySlug(string $slug): ?Product
    {
        return Product::query()
            ->with(['media', 'categories', 'related.media'])
            ->where('slug', $slug)
            ->first();
    }

    public function createProduct(ProductData $data, string $slug): Product
    {
        $product = Product::query()->create([
            'name' => $data->name,
            'slug' => $slug,
            'sku' => $data->sku,
            'price' => $data->price,
            'description' => $data->description,
            'position' => $data->position,
            'translations' => $data->translations !== [] ? $data->translations : null,
        ]);

        $this->sync($product, $data);

        return $product->refresh()->load(['media', 'categories', 'related']);
    }

    public function updateProduct(Product $product, ProductData $data, string $slug): Product
    {
        $product->update([
            'name' => $data->name,
            'slug' => $slug,
            'sku' => $data->sku,
            'price' => $data->price,
            'description' => $data->description,
            'position' => $data->position,
            'translations' => $data->translations !== [] ? $data->translations : null,
        ]);

        $this->sync($product, $data);

        return $product->refresh()->load(['media', 'categories', 'related']);
    }

    public function deleteProduct(Product $product): void
    {
        $product->delete();
    }

    public function productSlugExists(string $slug, ?Product $ignore = null): bool
    {
        return Product::query()
            ->where('slug', $slug)
            ->when($ignore, fn (Builder $query): Builder => $query->whereKeyNot($ignore->getKey()))
            ->exists();
    }

    private function sync(Product $product, ProductData $data): void
    {
        $media = [];

        foreach ($data->mediaIds as $position => $mediaId) {
            $media[$mediaId] = ['position' => $position];
        }

        $product->categories()->sync($data->categoryIds);
        $product->related()->sync(array_values(array_diff($data->relatedIds, [$product->id])));
        $product->media()->sync($media);
    }
}
