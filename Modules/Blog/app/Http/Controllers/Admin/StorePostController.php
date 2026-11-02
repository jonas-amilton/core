<?php

declare(strict_types=1);

namespace Modules\Blog\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Modules\Blog\Http\Requests\PostRequest;
use Modules\Blog\Services\BlogService;

final class StorePostController
{
    public function __invoke(PostRequest $request, BlogService $blog): RedirectResponse
    {
        $post = $blog->createPost($request->toData());

        return to_route('blog.edit', $post);
    }
}
