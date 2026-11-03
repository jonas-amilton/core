<?php

declare(strict_types=1);

namespace Modules\Blog\Http\Controllers\Site;

use Inertia\Inertia;
use Inertia\Response;
use Modules\Blog\Models\Category;
use Modules\Blog\Models\Post;
use Modules\Blog\Services\BlogService;
use Modules\Page\Services\SiteNavigation;

final class CategoryBlogController
{
    public function __invoke(Category $category, BlogService $blog, SiteNavigation $navigation): Response
    {
        return Inertia::render('Site::Blog/Feed', [
            'posts' => $blog->publishedPostsByCategory($category)
                ->map(fn (Post $post): array => $post->toPublicArray())
                ->all(),
            'categories' => $blog->categories()
                ->map(fn (Category $item): array => $item->toSiteArray())
                ->all(),
            'activeCategory' => $category->toSiteArray(),
            'navigation' => $navigation->items(),
        ]);
    }
}
