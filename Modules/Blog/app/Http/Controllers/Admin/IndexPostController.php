<?php

declare(strict_types=1);

namespace Modules\Blog\Http\Controllers\Admin;

use Inertia\Inertia;
use Inertia\Response;
use Modules\Blog\Models\Post;
use Modules\Blog\Services\BlogService;

final class IndexPostController
{
    public function __invoke(BlogService $blog): Response
    {
        return Inertia::render('Blog::Posts', [
            'posts' => $blog->posts()
                ->map(fn (Post $post): array => $post->toAdminArray())
                ->all(),
        ]);
    }
}
