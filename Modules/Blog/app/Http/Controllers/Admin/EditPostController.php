<?php

declare(strict_types=1);

namespace Modules\Blog\Http\Controllers\Admin;

use Inertia\Inertia;
use Inertia\Response;
use Modules\Blog\Models\Category;
use Modules\Blog\Models\Post;
use Modules\Blog\Services\BlogService;

final class EditPostController
{
    public function __invoke(Post $post, BlogService $blog): Response
    {
        return Inertia::render('Blog::Editor', [
            'post' => $post->toEditorArray(),
            'categories' => $blog->categories()
                ->map(fn (Category $category): array => $category->toAdminArray())
                ->all(),
        ]);
    }
}
