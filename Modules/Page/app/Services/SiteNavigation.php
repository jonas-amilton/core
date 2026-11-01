<?php

declare(strict_types=1);

namespace Modules\Page\Services;

use Modules\Page\Contracts\PageRepository;
use Modules\Page\Models\Page;

final class SiteNavigation
{
    public function __construct(private PageRepository $pages) {}

    /**
     * @return array<int, array{title: string, url: string, slug: string}>
     */
    public function items(): array
    {
        return $this->pages->publishedForNavigation()
            ->map(fn (Page $page): array => [
                'title' => $page->title,
                'slug' => $page->slug,
                'url' => $page->slug === 'home' ? '/' : '/'.$page->slug,
            ])
            ->values()
            ->all();
    }
}
