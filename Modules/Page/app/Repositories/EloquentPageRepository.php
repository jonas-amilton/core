<?php

declare(strict_types=1);

namespace Modules\Page\Repositories;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection;
use Modules\Page\Contracts\PageRepository;
use Modules\Page\Data\PageData;
use Modules\Page\Enums\PageStatus;
use Modules\Page\Models\Page;
use Modules\Page\Models\PageRevision;

final class EloquentPageRepository implements PageRepository
{
    /**
     * @return Collection<int, Page>
     */
    public function allForAdmin(): Collection
    {
        return Page::query()
            ->orderByRaw("case when slug = 'home' then 0 else 1 end")
            ->latest('updated_at')
            ->get();
    }

    public function find(int $id): ?Page
    {
        return Page::query()->find($id);
    }

    public function findPublishedBySlug(string $slug): ?Page
    {
        return Page::query()
            ->where('slug', $slug)
            ->where('status', '!=', PageStatus::Draft->value)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->first();
    }

    public function findHome(): ?Page
    {
        return $this->findBySlug(Page::HOME_SLUG);
    }

    public function findBySlug(string $slug): ?Page
    {
        return Page::query()->where('slug', $slug)->first();
    }

    public function create(PageData $data, PageStatus $status, ?CarbonInterface $publishedAt = null): Page
    {
        return Page::query()->create([
            'title' => $data->title,
            'slug' => (string) $data->slug,
            'icon' => $data->icon,
            'is_service' => $data->isService,
            'blocks' => $data->blocksAsArray(),
            'translations' => $data->translations !== [] ? $data->translations : null,
            'status' => $status,
            'published_at' => $publishedAt,
        ]);
    }

    public function update(Page $page, PageData $data): Page
    {
        $page->update([
            'title' => $data->title,
            'slug' => $data->slug ?? $page->slug,
            'icon' => $data->icon,
            'is_service' => $data->isService,
            'blocks' => $data->blocksAsArray(),
            'translations' => $data->translations !== [] ? $data->translations : null,
        ]);

        return $page->refresh();
    }

    public function updateStatus(Page $page, PageStatus $status, ?CarbonInterface $publishedAt): Page
    {
        $page->update([
            'status' => $status,
            'published_at' => $publishedAt,
        ]);

        return $page->refresh();
    }

    public function delete(Page $page): void
    {
        $page->delete();
    }

    public function snapshot(Page $page, ?int $userId): PageRevision
    {
        return $page->revisions()->create([
            'title' => $page->title,
            'blocks' => $page->blocks,
            'created_by' => $userId,
        ]);
    }

    /**
     * @return Collection<int, PageRevision>
     */
    public function revisions(Page $page, int $limit = 10): Collection
    {
        return $page->revisions()->with('author:id,name')->limit($limit)->get();
    }

    /**
     * @return Collection<int, Page>
     */
    public function publishedForNavigation(): Collection
    {
        return Page::query()
            ->where('status', '!=', PageStatus::Draft->value)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->whereNotIn('slug', [Page::NOT_FOUND_SLUG])
            ->orderByRaw("case when slug = 'home' then 0 else 1 end")
            ->orderBy('created_at')
            ->get(['id', 'title', 'slug']);
    }

    public function slugExists(string $slug, ?Page $ignore = null): bool
    {
        return Page::query()
            ->where('slug', $slug)
            ->when($ignore, fn ($query) => $query->whereKeyNot($ignore->getKey()))
            ->exists();
    }

    public function countPublished(): int
    {
        return Page::query()
            ->where('status', '!=', PageStatus::Draft->value)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->count();
    }

    /**
     * @return Collection<int, Page>
     */
    public function publishedServices(): Collection
    {
        return Page::query()
            ->where('is_service', true)
            ->where('status', '!=', PageStatus::Draft->value)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->orderBy('created_at')
            ->get(['id', 'title', 'slug', 'icon']);
    }

    public function countUsingMedia(int $mediaId): int
    {
        return Page::query()
            ->get(['id', 'blocks'])
            ->filter(fn (Page $page): bool => collect($page->blocks ?? [])->contains(
                fn (array $block): bool => (int) ($block['media_id'] ?? 0) === $mediaId,
            ))
            ->count();
    }
}
