<?php

declare(strict_types=1);

namespace Modules\Blog\Http\Controllers\Site;

use Inertia\Inertia;
use Inertia\Response;
use Modules\Blog\Models\Category;
use Modules\Blog\Models\Post;
use Modules\Blog\Services\BlogService;
use Modules\Page\Services\SiteNavigation;

final class IndexBlogController
{
    public function __invoke(BlogService $blog, SiteNavigation $navigation): Response
    {
        return Inertia::render('Site::Blog/Feed', [
            'posts' => $blog->publishedPosts(12)
                ->map(fn (Post $post): array => $post->toPublicArray())
                ->all(),
            'categories' => $blog->categories()
                ->map(fn (Category $category): array => $category->toSiteArray())
                ->all(),
            'activeCategory' => null,
            'navigation' => $navigation->items(),
        ]);
    }
}
