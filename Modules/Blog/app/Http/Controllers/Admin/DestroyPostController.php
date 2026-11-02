<?php

declare(strict_types=1);

namespace Modules\Blog\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Modules\Blog\Models\Post;
use Modules\Blog\Services\BlogService;

final class DestroyPostController
{
    public function __invoke(Post $post, BlogService $blog): RedirectResponse
    {
        $blog->deletePost($post);

        return to_route('blog.index');
    }
}
