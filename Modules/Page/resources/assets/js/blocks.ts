import type { Block, BlockType, PageStatus } from './types';

export const blockLabels: Record<BlockType, string> = {
    hero: 'Hero',
    text: 'Texto',
    grid: 'Grid',
    image: 'Imagem',
    cta: 'CTA',
};

const uid = (): string =>
    globalThis.crypto?.randomUUID?.() ??
    `b${Date.now().toString(36)}${Math.random().toString(36).slice(2, 8)}`;

export const newBlockId = uid;

export function createBlock(type: BlockType): Block {
    const base = { id: uid(), type };

    switch (type) {
        case 'hero':
            return {
                ...base,
                title: 'Título do hero',
                subtitle: 'Um subtítulo curto explicando a proposta.',
                button_label: 'Saiba mais',
                button_href: '#',
            };
        case 'text':
            return { ...base, body: 'Escreva o texto da seção.' };
        case 'grid':
            return {
                ...base,
                items: [
                    { title: 'Primeiro item', description: '' },
                    { title: 'Segundo item', description: '' },
                    { title: 'Terceiro item', description: '' },
                ],
            };
        case 'image':
            return { ...base, src: '', alt: '' };
        case 'cta':
            return {
                ...base,
                title: 'Pronto para começar?',
                description: '',
                button_label: 'Começar',
                button_href: '#',
            };
    }
}

export type Template = {
    id: string;
    label: string;
    description: string;
    build: () => Block[];
};

export const templates: Template[] = [
    {
        id: 'blank',
        label: 'Em branco',
        description: 'Comece do zero e adicione apenas o que precisar.',
        build: () => [],
    },
    {
        id: 'landing',
        label: 'Landing',
        description: 'Hero, grid de benefícios e CTA final.',
        build: () => [
            createBlock('hero'),
            createBlock('grid'),
            createBlock('cta'),
        ],
    },
    {
        id: 'about',
        label: 'Sobre',
        description: 'Hero, texto corrido e uma imagem de destaque.',
        build: () => [
            createBlock('hero'),
            createBlock('text'),
            createBlock('image'),
        ],
    },
];

export function blockSummary(block: Block): string {
    if (block.type === 'grid') {
        return `${block.items?.length ?? 0} item(ns)`;
    }

    const text = block.title || block.body || block.alt || '';

    return text.length > 42 ? `${text.slice(0, 42)}…` : text;
}

export const statusLabels: Record<PageStatus, string> = {
    draft: 'Rascunho',
    published: 'Publicado',
    scheduled: 'Agendado',
};

export const statusClasses: Record<PageStatus, string> = {
    draft: 'border-slate-200 bg-slate-100 text-slate-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300',
    published:
        'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-300',
    scheduled:
        'border-indigo-200 bg-indigo-50 text-indigo-700 dark:border-indigo-500/30 dark:bg-indigo-500/10 dark:text-indigo-300',
};
