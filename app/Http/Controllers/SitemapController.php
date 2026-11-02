<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Modules\Blog\Models\Post;
use Modules\Catalog\Models\Product;
use Modules\Page\Enums\PageStatus;
use Modules\Page\Models\Page;

final class SitemapController
{
    public function __invoke(): Response
    {
        $urls = [
            ['loc' => url('/'), 'lastmod' => null],
            ['loc' => url('/produtos'), 'lastmod' => null],
            ['loc' => url('/blog'), 'lastmod' => null],
            ['loc' => url('/onde-comprar'), 'lastmod' => null],
        ];

        Page::query()
            ->where('status', PageStatus::Published)
            ->whereNotIn('slug', [Page::HOME_SLUG, Page::NOT_FOUND_SLUG])
            ->get(['slug', 'updated_at'])
            ->each(function (Page $page) use (&$urls): void {
                $urls[] = [
                    'loc' => url('/'.$page->slug),
                    'lastmod' => $page->updated_at?->toAtomString(),
                ];
            });

        Post::query()
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->get(['slug', 'updated_at'])
            ->each(function (Post $post) use (&$urls): void {
                $urls[] = [
                    'loc' => url('/blog/'.$post->slug),
                    'lastmod' => $post->updated_at?->toAtomString(),
                ];
            });

        Product::query()
            ->get(['slug', 'updated_at'])
            ->each(function (Product $product) use (&$urls): void {
                $urls[] = [
                    'loc' => url('/produto/'.$product->slug),
                    'lastmod' => $product->updated_at?->toAtomString(),
                ];
            });

        return response(
            '<?xml version="1.0" encoding="UTF-8"?>'."\n".view('sitemap', ['urls' => $urls])->render(),
            200,
            ['Content-Type' => 'application/xml'],
        );
    }
}
