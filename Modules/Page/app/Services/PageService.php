<?php

declare(strict_types=1);

namespace Modules\Page\Services;

use Carbon\CarbonInterface;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Modules\Page\Contracts\PageRepository;
use Modules\Page\Data\BlockData;
use Modules\Page\Data\PageData;
use Modules\Page\Enums\PageStatus;
use Modules\Page\Models\Page;
use Modules\Page\Models\PageRevision;
use Modules\Page\Support\ReservedSlugs;

final class PageService
{
    public function __construct(private PageRepository $pages) {}

    public function create(PageData $data): Page
    {
        $slug = $this->resolveSlug($data->slug ?? $data->title);

        return $this->pages->create($data->withSlug($slug), PageStatus::Draft);
    }

    public function update(Page $page, PageData $data): Page
    {
        return $this->pages->update($page, $data);
    }

    public function publish(Page $page, ?CarbonInterface $publishedAt, ?int $userId): Page
    {
        $at = $publishedAt ?? now();
        $status = $at->isFuture() ? PageStatus::Scheduled : PageStatus::Published;

        $this->pages->snapshot($page, $userId);

        return $this->pages->updateStatus($page, $status, $at);
    }

    public function unpublish(Page $page): Page
    {
        $this->guardSystem($page, 'despublicada');

        return $this->pages->updateStatus($page, PageStatus::Draft, null);
    }

    public function delete(Page $page): void
    {
        $this->guardSystem($page, 'excluída');

        $this->pages->delete($page);
    }

    public function restore(Page $page, PageRevision $revision, ?int $userId): Page
    {
        $this->pages->snapshot($page, $userId);

        $blocks = array_map(
            fn (array $block): BlockData => BlockData::fromArray($block),
            $revision->blocks ?? [],
        );

        return $this->pages->update(
            $page,
            new PageData(
                $revision->title,
                $page->slug,
                $blocks,
                $page->icon,
                $page->is_service,
                $page->translationsForEditor(),
            ),
        );
    }

    private function resolveSlug(string $base): string
    {
        $slug = Str::slug($base) ?: 'pagina';

        if (ReservedSlugs::contains($slug)) {
            throw ValidationException::withMessages([
                'title' => "O endereço \"/{$slug}\" é reservado pelo sistema. Ajuste o título da página.",
            ]);
        }

        $candidate = $slug;
        $suffix = 2;

        while ($this->pages->slugExists($candidate)) {
            $candidate = "{$slug}-{$suffix}";
            $suffix++;
        }

        return $candidate;
    }

    private function guardSystem(Page $page, string $action): void
    {
        if (! $page->isSystem()) {
            return;
        }

        $label = $page->isHome() ? 'A página inicial' : 'A página 404';

        throw ValidationException::withMessages([
            'page' => "{$label} não pode ser {$action}.",
        ]);
    }
}
