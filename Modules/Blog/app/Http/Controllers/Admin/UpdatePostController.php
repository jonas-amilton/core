<?php

declare(strict_types=1);

namespace Modules\Blog\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Modules\Blog\Http\Requests\PostRequest;
use Modules\Blog\Models\Post;
use Modules\Blog\Services\BlogService;

final class UpdatePostController
{
    public function __invoke(PostRequest $request, Post $post, BlogService $blog): RedirectResponse
    {
        $blog->updatePost($post, $request->toData());

        return to_route('blog.edit', $post);
    }
}
