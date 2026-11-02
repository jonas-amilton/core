<?php

declare(strict_types=1);

namespace Modules\Blog\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Modules\Blog\Models\Category;
use Modules\Blog\Services\BlogService;

final class DestroyCategoryController
{
    public function __invoke(Category $category, BlogService $blog): RedirectResponse
    {
        $blog->deleteCategory($category);

        return to_route('blog.category.index');
    }
}
