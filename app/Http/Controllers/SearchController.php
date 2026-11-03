<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Support\SearchTerm;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Blog\Models\Post;
use Modules\Catalog\Models\Product;
use Modules\Page\Enums\PageStatus;
use Modules\Page\Models\Page;
use Modules\Page\Services\SiteNavigation;

final class SearchController
{
    public function __invoke(Request $request, SiteNavigation $navigation): Response
    {
        $term = trim((string) $request->query('q', ''));
        $needle = '%'.SearchTerm::fold($term).'%';

        $products = [];
        $posts = [];
        $pages = [];

        if ($term !== '') {
            $products = Product::query()
                ->with('media')
                ->where('search_text', 'like', $needle)
                ->orderBy('position')
                ->limit(24)
                ->get()
                ->map(fn (Product $product): array => $product->toSiteArray())
                ->values()
                ->all();

            $posts = Post::query()
                ->whereNotNull('published_at')
                ->where('published_at', '<=', now())
                ->where('search_text', 'like', $needle)
                ->latest('published_at')
                ->limit(12)
                ->get(['title', 'slug', 'excerpt', 'cover'])
                ->map(fn (Post $post): array => [
                    'title' => $post->title,
                    'slug' => $post->slug,
                    'excerpt' => $post->excerpt,
                    'cover' => $post->cover,
                ])
                ->values()
                ->all();

            $pages = Page::query()
                ->where('status', PageStatus::Published)
                ->where('slug', '!=', Page::NOT_FOUND_SLUG)
                ->where('search_text', 'like', $needle)
                ->orderBy('title')
                ->limit(12)
                ->get(['title', 'slug'])
                ->map(fn (Page $page): array => [
                    'title' => $page->title,
                    'url' => $page->slug === Page::HOME_SLUG ? '/' : '/'.$page->slug,
                ])
                ->values()
                ->all();
        }

        return Inertia::render('Site::Search', [
            'term' => $term,
            'products' => $products,
            'posts' => $posts,
            'pages' => $pages,
            'navigation' => $navigation->items(),
        ]);
    }
}
