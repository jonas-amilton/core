<?php

declare(strict_types=1);

namespace Modules\Page\Services;

use Modules\Blog\Models\Post;
use Modules\Blog\Services\BlogService;
use Modules\Content\Models\Client;
use Modules\Content\Models\Faq;
use Modules\Content\Models\Testimonial;
use Modules\Content\Services\ContentService;
use Modules\Page\Contracts\PageRepository;
use Modules\Page\Models\Page;

/**
 * Attaches the live content a block lists (published rows, never stored in the
 * page JSON) so the site renders from one payload.
 */
final class BlockContentResolver
{
    public function __construct(
        private PageRepository $pages,
        private ContentService $content,
        private BlogService $blog,
    ) {}

    /**
     * @param  array<int, array<string, mixed>>  $blocks
     * @return array<int, array<string, mixed>>
     */
    public function resolve(array $blocks): array
    {
        return array_map(function (array $block): array {
            $items = match ($block['type'] ?? null) {
                'services' => $this->services(),
                'testimonials' => $this->testimonials(),
                'faq' => $this->faqs(),
                'clients' => $this->clients(),
                'posts' => $this->posts((int) ($block['limit'] ?? 3)),
                default => null,
            };

            if ($items !== null) {
                $block['items'] = $items;
            }

            return $block;
        }, $blocks);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function services(): array
    {
        return $this->pages->publishedServices()
            ->map(fn (Page $page): array => [
                'title' => $page->title,
                'url' => '/'.$page->slug,
                'icon' => $page->icon,
            ])
            ->values()
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function testimonials(): array
    {
        return $this->content->testimonials()
            ->map(fn (Testimonial $testimonial): array => $testimonial->toCardArray())
            ->values()
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function faqs(): array
    {
        return $this->content->faqs()
            ->map(fn (Faq $faq): array => $faq->toCardArray())
            ->values()
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function clients(): array
    {
        return $this->content->clients()
            ->map(fn (Client $client): array => $client->toCardArray())
            ->values()
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function posts(int $limit): array
    {
        $limit = max(1, min(12, $limit));

        return $this->blog->publishedPosts($limit)
            ->map(fn (Post $post): array => $post->toCardArray())
            ->values()
            ->all();
    }
}
