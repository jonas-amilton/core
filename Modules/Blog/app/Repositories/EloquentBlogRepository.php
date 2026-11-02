<?php

declare(strict_types=1);

namespace Modules\Blog\Repositories;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection;
use Modules\Blog\Contracts\BlogRepository;
use Modules\Blog\Data\CategoryData;
use Modules\Blog\Data\PostData;
use Modules\Blog\Models\Category;
use Modules\Blog\Models\Post;

final class EloquentBlogRepository implements BlogRepository
{
    /**
     * @return Collection<int, Post>
     */
    public function posts(): Collection
    {
        return Post::query()->with('category')->latest('id')->get();
    }

    public function findPost(int $id): ?Post
    {
        return Post::query()->with('category')->find($id);
    }

    public function findPublishedPostBySlug(string $slug): ?Post
    {
        return Post::query()
            ->with('category')
            ->where('slug', $slug)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->first();
    }

    /**
     * @return Collection<int, Post>
     */
    public function publishedPosts(int $limit = 20): Collection
    {
        return Post::query()
            ->with('category')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->limit($limit)
            ->get();
    }

    /**
     * @return Collection<int, Post>
     */
    public function publishedPostsByCategory(Category $category, int $limit = 20): Collection
    {
        return Post::query()
            ->with('category')
            ->where('category_id', $category->id)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->limit($limit)
            ->get();
    }

    public function createPost(PostData $data, string $slug, ?CarbonInterface $publishedAt): Post
    {
        return Post::query()->create([
            'category_id' => $data->categoryId,
            'title' => $data->title,
            'slug' => $slug,
            'excerpt' => $data->excerpt,
            'body' => $data->body,
            'cover' => $data->cover,
            'published_at' => $publishedAt,
            'translations' => $data->translations !== [] ? $data->translations : null,
        ]);
    }

    public function updatePost(Post $post, PostData $data, string $slug, ?CarbonInterface $publishedAt): Post
    {
        $post->update([
            'category_id' => $data->categoryId,
            'title' => $data->title,
            'slug' => $slug,
            'excerpt' => $data->excerpt,
            'body' => $data->body,
            'cover' => $data->cover,
            'published_at' => $publishedAt,
            'translations' => $data->translations !== [] ? $data->translations : null,
        ]);

        return $post->refresh()->load('category');
    }

    public function deletePost(Post $post): void
    {
        $post->delete();
    }

    /**
     * @return Collection<int, Category>
     */
    public function categories(): Collection
    {
        return Category::query()->orderBy('name')->get();
    }

    public function findCategory(int $id): ?Category
    {
        return Category::query()->find($id);
    }

    public function findCategoryBySlug(string $slug): ?Category
    {
        return Category::query()->where('slug', $slug)->first();
    }

    public function createCategory(CategoryData $data, string $slug): Category
    {
        return Category::query()->create([
            'name' => $data->name,
            'slug' => $slug,
            'translations' => $data->translations !== [] ? $data->translations : null,
        ]);
    }

    public function updateCategory(Category $category, CategoryData $data, string $slug): Category
    {
        $category->update([
            'name' => $data->name,
            'slug' => $slug,
            'translations' => $data->translations !== [] ? $data->translations : null,
        ]);

        return $category->refresh();
    }

    public function deleteCategory(Category $category): void
    {
        $category->delete();
    }

    public function postSlugExists(string $slug, ?Post $ignore = null): bool
    {
        return Post::query()
            ->where('slug', $slug)
            ->when($ignore, fn ($query) => $query->whereKeyNot($ignore->getKey()))
            ->exists();
    }

    public function categorySlugExists(string $slug, ?Category $ignore = null): bool
    {
        return Category::query()
            ->where('slug', $slug)
            ->when($ignore, fn ($query) => $query->whereKeyNot($ignore->getKey()))
            ->exists();
    }
}
