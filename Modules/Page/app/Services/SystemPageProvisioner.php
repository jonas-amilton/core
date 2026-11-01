<?php

declare(strict_types=1);

namespace Modules\Page\Services;

use Modules\Page\Contracts\PageRepository;
use Modules\Page\Data\BlockData;
use Modules\Page\Data\PageData;
use Modules\Page\Enums\BlockType;
use Modules\Page\Enums\PageStatus;
use Modules\Page\Models\Page;

/**
 * System pages always exist: they are provisioned on first use and can only be
 * edited afterwards (never deleted or unpublished).
 */
final class SystemPageProvisioner
{
    public function __construct(private PageRepository $pages) {}

    public function home(): Page
    {
        return $this->pages->findHome() ?? $this->pages->create(
            new PageData(
                title: 'Home',
                slug: Page::HOME_SLUG,
                blocks: [
                    new BlockData(
                        id: 'home-hero',
                        type: BlockType::Hero,
                        title: 'Bem-vindo ao seu site',
                        subtitle: 'Edite esta página no painel para publicar seu conteúdo.',
                        buttonLabel: 'Fale com a gente',
                        buttonHref: '#contato',
                    ),
                ],
            ),
            PageStatus::Published,
            now(),
        );
    }

    public function notFound(): Page
    {
        return $this->pages->findBySlug(Page::NOT_FOUND_SLUG) ?? $this->pages->create(
            new PageData(
                title: 'Página não encontrada',
                slug: Page::NOT_FOUND_SLUG,
                blocks: [
                    new BlockData(
                        id: 'not-found-hero',
                        type: BlockType::Hero,
                        title: 'Página não encontrada',
                        subtitle: 'O endereço que você tentou acessar não existe ou foi movido.',
                        buttonLabel: 'Voltar para o início',
                        buttonHref: '/',
                    ),
                ],
            ),
            PageStatus::Published,
            now(),
        );
    }
}
