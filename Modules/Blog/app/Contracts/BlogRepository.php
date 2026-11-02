<?php

declare(strict_types=1);

namespace Modules\Blog\Contracts;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection;
use Modules\Blog\Data\CategoryData;
use Modules\Blog\Data\PostData;
use Modules\Blog\Models\Category;
use Modules\Blog\Models\Post;

interface BlogRepository
{
    /**
     * @return Collection<int, Post>
     */
    public function posts(): Collection;

    public function findPost(int $id): ?Post;

    public function findPublishedPostBySlug(string $slug): ?Post;

    /**
     * @return Collection<int, Post>
     */
    public function publishedPosts(int $limit = 20): Collection;

    /**
     * @return Collection<int, Post>
     */
    public function publishedPostsByCategory(Category $category, int $limit = 20): Collection;

    public function createPost(PostData $data, string $slug, ?CarbonInterface $publishedAt): Post;

    public function updatePost(Post $post, PostData $data, string $slug, ?CarbonInterface $publishedAt): Post;

    public function deletePost(Post $post): void;

    /**
     * @return Collection<int, Category>
     */
    public function categories(): Collection;

    public function findCategory(int $id): ?Category;

    public function findCategoryBySlug(string $slug): ?Category;

    public function createCategory(CategoryData $data, string $slug): Category;

    public function updateCategory(Category $category, CategoryData $data, string $slug): Category;

    public function deleteCategory(Category $category): void;

    public function postSlugExists(string $slug, ?Post $ignore = null): bool;

    public function categorySlugExists(string $slug, ?Category $ignore = null): bool;
}
