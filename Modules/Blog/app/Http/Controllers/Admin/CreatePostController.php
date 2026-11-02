<?php

declare(strict_types=1);

namespace Modules\Blog\Http\Controllers\Admin;

use Inertia\Inertia;
use Inertia\Response;
use Modules\Blog\Models\Category;
use Modules\Blog\Services\BlogService;

final class CreatePostController
{
    public function __invoke(BlogService $blog): Response
    {
        return Inertia::render('Blog::Editor', [
            'post' => null,
            'categories' => $blog->categories()
                ->map(fn (Category $category): array => $category->toAdminArray())
                ->all(),
        ]);
    }
}
