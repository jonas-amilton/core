<?php

declare(strict_types=1);

namespace Modules\Blog\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Modules\Blog\Http\Requests\CategoryRequest;
use Modules\Blog\Services\BlogService;

final class StoreCategoryController
{
    public function __invoke(CategoryRequest $request, BlogService $blog): RedirectResponse
    {
        $blog->createCategory($request->toData());

        return to_route('blog.category.index');
    }
}
