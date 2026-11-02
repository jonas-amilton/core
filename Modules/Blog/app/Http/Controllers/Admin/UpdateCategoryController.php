<?php

declare(strict_types=1);

namespace Modules\Blog\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Modules\Blog\Http\Requests\CategoryRequest;
use Modules\Blog\Models\Category;
use Modules\Blog\Services\BlogService;

final class UpdateCategoryController
{
    public function __invoke(CategoryRequest $request, Category $category, BlogService $blog): RedirectResponse
    {
        $blog->updateCategory($category, $request->toData());

        return to_route('blog.category.index');
    }
}
