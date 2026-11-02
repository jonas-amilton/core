<?php

declare(strict_types=1);

namespace Modules\Blog\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Modules\Blog\Contracts\BlogRepository;
use Modules\Blog\Data\CategoryData;
use Modules\Blog\Data\PostData;
use Modules\Blog\Models\Category;
use Modules\Blog\Models\Post;

final class BlogService
{
    public function __construct(private BlogRepository $blog) {}

    /**
     * @return Collection<int, Post>
     */
    public function posts(): Collection
    {
        return $this->blog->posts();
    }

    /**
     * @return Collection<int, Post>
     */
    public function publishedPosts(int $limit = 20): Collection
    {
        return $this->blog->publishedPosts($limit);
    }

    /**
     * @return Collection<int, Post>
     */
    public function publishedPostsByCategory(Category $category, int $limit = 20): Collection
    {
        return $this->blog->publishedPostsByCategory($category, $limit);
    }

    public function createPost(PostData $data): Post
    {
        $slug = $this->resolvePostSlug($data->slug ?? $data->title, null);

        return $this->blog->createPost($data, $slug, $data->published ? now() : null);
    }

    public function updatePost(Post $post, PostData $data): Post
    {
        $slug = $data->slug !== null && $data->slug !== $post->slug
            ? $this->resolvePostSlug($data->slug, $post)
            : $post->slug;

        $publishedAt = $data->published
            ? ($post->published_at ?? now())
            : null;

        return $this->blog->updatePost($post, $data, $slug, $publishedAt);
    }

    public function deletePost(Post $post): void
    {
        $this->blog->deletePost($post);
    }

    /**
     * @return Collection<int, Category>
     */
    public function categories(): Collection
    {
        return $this->blog->categories();
    }

    public function createCategory(CategoryData $data): Category
    {
        $slug = $this->resolveCategorySlug($data->slug ?? $data->name, null);

        return $this->blog->createCategory($data, $slug);
    }

    public function updateCategory(Category $category, CategoryData $data): Category
    {
        $slug = $data->slug !== null && $data->slug !== $category->slug
            ? $this->resolveCategorySlug($data->slug, $category)
            : $category->slug;

        return $this->blog->updateCategory($category, $data, $slug);
    }

    public function deleteCategory(Category $category): void
    {
        $this->blog->deleteCategory($category);
    }

    private function resolvePostSlug(string $base, ?Post $ignore): string
    {
        $slug = Str::slug($base) ?: 'sem-titulo';
        $candidate = $slug;
        $suffix = 2;

        while ($this->blog->postSlugExists($candidate, $ignore)) {
            $candidate = "{$slug}-{$suffix}";
            $suffix++;
        }

        return $candidate;
    }

    private function resolveCategorySlug(string $base, ?Category $ignore): string
    {
        $slug = Str::slug($base) ?: 'sem-categoria';
        $candidate = $slug;
        $suffix = 2;

        while ($this->blog->categorySlugExists($candidate, $ignore)) {
            $candidate = "{$slug}-{$suffix}";
            $suffix++;
        }

        return $candidate;
    }
}
