<?php

declare(strict_types=1);

namespace Modules\Blog\Http\Controllers\Site;

use Inertia\Inertia;
use Inertia\Response;
use Modules\Blog\Models\Category;
use Modules\Blog\Models\Post;
use Modules\Blog\Services\BlogService;
use Modules\Page\Services\SiteNavigation;

final class ShowBlogController
{
    public function __invoke(Post $post, BlogService $blog, SiteNavigation $navigation): Response
    {
        abort_unless($post->isPublished(), 404);

        return Inertia::render('Blog::Article', [
            'post' => $post->toPublicArray(),
            'navigation' => $navigation->items(),
            'latest' => $blog->publishedPosts(3)
                ->reject(fn (Post $item): bool => $item->id === $post->id)
                ->map(fn (Post $item): array => $item->toPublicArray())
                ->values()
                ->all(),
            'categories' => $blog->categories()
                ->map(fn (Category $category): array => $category->toSiteArray())
                ->all(),
        ]);
    }
}
