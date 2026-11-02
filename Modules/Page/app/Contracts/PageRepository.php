<?php

declare(strict_types=1);

namespace Modules\Page\Contracts;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection;
use Modules\Page\Data\PageData;
use Modules\Page\Enums\PageStatus;
use Modules\Page\Models\Page;
use Modules\Page\Models\PageRevision;

interface PageRepository
{
    /**
     * @return Collection<int, Page>
     */
    public function allForAdmin(): Collection;

    public function find(int $id): ?Page;

    public function findPublishedBySlug(string $slug): ?Page;

    public function findHome(): ?Page;

    public function findBySlug(string $slug): ?Page;

    public function create(PageData $data, PageStatus $status, ?CarbonInterface $publishedAt = null): Page;

    public function update(Page $page, PageData $data): Page;

    public function updateStatus(Page $page, PageStatus $status, ?CarbonInterface $publishedAt): Page;

    public function delete(Page $page): void;

    public function snapshot(Page $page, ?int $userId): PageRevision;

    /**
     * @return Collection<int, PageRevision>
     */
    public function revisions(Page $page, int $limit = 10): Collection;

    /**
     * @return Collection<int, Page>
     */
    public function publishedForNavigation(): Collection;

    /**
     * @return Collection<int, Page>
     */
    public function publishedServices(): Collection;

    public function slugExists(string $slug, ?Page $ignore = null): bool;

    public function countPublished(): int;

    /**
     * How many pages reference a media item in any block.
     */
    public function countUsingMedia(int $mediaId): int;
}
