<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import {
    ArrowLeft,
    CalendarClock,
    ChevronDown,
    ChevronUp,
    Copy,
    Eye,
    EyeOff,
    ExternalLink,
    GripVertical,
    Image as ImageIcon,
    Plus,
    Trash2,
} from '@lucide/vue';
import {
    computed,
    onBeforeUnmount,
    onMounted,
    ref,
    useTemplateRef,
    watch,
} from 'vue';
import CtaBlock from '../Blocks/CtaBlock.vue';
import ClientsBlock from '../Blocks/ClientsBlock.vue';
import CareerBlock from '../Blocks/CareerBlock.vue';
import CategoriesBlock from '../Blocks/CategoriesBlock.vue';
import ContactBlock from '../Blocks/ContactBlock.vue';
import FaqBlock from '../Blocks/FaqBlock.vue';
import GridBlock from '../Blocks/GridBlock.vue';
import HeroBlock from '../Blocks/HeroBlock.vue';
import ImageBlock from '../Blocks/ImageBlock.vue';
import NewsletterBlock from '../Blocks/NewsletterBlock.vue';
import PostsBlock from '../Blocks/PostsBlock.vue';
import ProductsBlock from '../Blocks/ProductsBlock.vue';
import ServicesBlock from '../Blocks/ServicesBlock.vue';
import TestimonialsBlock from '../Blocks/TestimonialsBlock.vue';
import TextBlock from '../Blocks/TextBlock.vue';
import MediaPicker from '@modules/Media/resources/assets/js/components/MediaPicker.vue';
import {
    blockLabels,
    blockSummary,
    createBlock,
    newBlockId,
    statusClasses,
    statusLabels,
    templates,
} from '../blocks';
import TextField from '../components/TextField.vue';
import type {
    Block,
    BlockType,
    PageLocale,
    PageRecord,
    PickedMedia,
    Revision,
} from '../types';
import { index as pagesIndex } from '@/routes/page';
import { preview, publish, store, unpublish, update } from '@/routes/page';
import { restore } from '@/routes/page/revisions';

const props = defineProps<{
    page: PageRecord | null;
    revisions: Revision[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Páginas',
                href: pagesIndex(),
            },
        ],
    },
});

const renderers = {
    hero: HeroBlock,
    text: TextBlock,
    grid: GridBlock,
    image: ImageBlock,
    cta: CtaBlock,
    services: ServicesBlock,
    testimonials: TestimonialsBlock,
    faq: FaqBlock,
    clients: ClientsBlock,
    posts: PostsBlock,
    products: ProductsBlock,
    categories: CategoriesBlock,
    contact: ContactBlock,
    career: CareerBlock,
    newsletter: NewsletterBlock,
};

const blockTypes = Object.keys(blockLabels) as BlockType[];

const pageLocales: PageLocale[] = ['pt_BR', 'en', 'es'];
const localeLabels: Record<PageLocale, string> = {
    pt_BR: 'PT',
    en: 'EN',
    es: 'ES',
};

const localeDrafts = ref<
    Record<PageLocale, { title: string; blocks: Block[] }>
>({
    pt_BR: {
        title: props.page?.title ?? 'Nova página',
        blocks: props.page?.blocks ?? [],
    },
    en: {
        title: props.page?.translations?.en?.title ?? '',
        blocks: props.page?.translations?.en?.blocks ?? [],
    },
    es: {
        title: props.page?.translations?.es?.title ?? '',
        blocks: props.page?.translations?.es?.blocks ?? [],
    },
});

const editLocale = ref<PageLocale>('pt_BR');
const pageId = ref<number | null>(props.page?.id ?? null);
const title = ref(localeDrafts.value.pt_BR.title);
const blocks = ref<Block[]>(localeDrafts.value.pt_BR.blocks);
const selectedId = ref<string | null>(blocks.value[0]?.id ?? null);
const pageIcon = ref(props.page?.icon ?? '');
const isService = ref(props.page?.is_service ?? false);

const dirty = ref(false);
const saving = ref(false);
const savedAt = ref<string | null>(null);
const errorMessage = ref<string | null>(null);
const scheduleOpen = ref(false);
const pickerOpen = ref(false);
const scheduleAt = ref('');
const draggingId = ref<string | null>(null);
const pickerTarget = ref<'block' | 'page'>('block');

const selected = computed(
    () => blocks.value.find((block) => block.id === selectedId.value) ?? null,
);

const status = computed(() => props.page?.status ?? 'draft');

const isHome = computed(() => props.page?.is_home === true);

const isNotFound = computed(() => props.page?.is_not_found === true);

const isSystem = computed(() => props.page?.is_system === true);

const publicUrl = computed(() => {
    if (!pageId.value || status.value !== 'published' || isNotFound.value) {
        return null;
    }

    return isHome.value ? '/' : `/${props.page?.slug}`;
});

const saveLabel = computed(() => {
    if (saving.value) {
        return 'Salvando…';
    }

    if (errorMessage.value) {
        return errorMessage.value;
    }

    if (dirty.value) {
        return 'Alterações não salvas';
    }

    return savedAt.value ? `Salvo às ${savedAt.value}` : 'Tudo salvo';
});

let saveTimer: ReturnType<typeof setTimeout> | null = null;

function schedule(): void {
    if (saveTimer) {
        clearTimeout(saveTimer);
    }

    saveTimer = setTimeout(save, 700);
}

function switchPageLocale(target: PageLocale): void {
    if (target === editLocale.value) {
        return;
    }

    localeDrafts.value[editLocale.value] = {
        title: title.value,
        blocks: blocks.value,
    };
    editLocale.value = target;

    const draft = localeDrafts.value[target];
    const useCopy = draft.blocks.length === 0 && target !== 'pt_BR';

    title.value = draft.title;
    blocks.value = useCopy
        ? structuredClone(localeDrafts.value.pt_BR.blocks)
        : draft.blocks;

    localeDrafts.value[target] = {
        title: title.value,
        blocks: blocks.value,
    };
    selectedId.value = blocks.value[0]?.id ?? null;
}

function save(): void {
    if (saving.value) {
        schedule();

        return;
    }

    localeDrafts.value[editLocale.value] = {
        title: title.value,
        blocks: blocks.value,
    };
    const base = localeDrafts.value.pt_BR;

    const payload = {
        title: base.title.trim() || 'Nova página',
        slug: props.page?.slug ?? null,
        icon: pageIcon.value || null,
        is_service: isService.value,
        blocks: base.blocks,
        translations: {
            en: {
                title: localeDrafts.value.en.title || null,
                blocks: localeDrafts.value.en.blocks,
            },
            es: {
                title: localeDrafts.value.es.title || null,
                blocks: localeDrafts.value.es.blocks,
            },
        },
    };

    saving.value = true;
    errorMessage.value = null;

    const options = {
        preserveScroll: true,
        preserveState: true,
        onSuccess: (response: { props: Record<string, unknown> }) => {
            const created = (response.props as { page?: PageRecord | null })
                .page;

            if (created?.id) {
                pageId.value = created.id;
            }

            savedAt.value = new Date().toLocaleTimeString('pt-BR', {
                hour: '2-digit',
                minute: '2-digit',
            });
            dirty.value = false;
        },
        onError: (errors: Record<string, string>) => {
            errorMessage.value =
                Object.values(errors)[0] ?? 'Não foi possível salvar.';
        },
        onFinish: () => {
            saving.value = false;
        },
    };

    if (pageId.value) {
        router.patch(update.url(pageId.value), payload, options);
    } else {
        router.post(store.url(), payload, options);
    }
}

watch(
    [title, blocks, pageIcon, isService],
    () => {
        dirty.value = true;
        errorMessage.value = null;
        schedule();
    },
    { deep: true },
);

function applyTemplate(templateId: string): void {
    const template = templates.find((item) => item.id === templateId);

    if (!template) {
        return;
    }

    blocks.value = template.build();
    selectedId.value = blocks.value[0]?.id ?? null;
}

const addMenu = useTemplateRef<HTMLDetailsElement>('addMenu');

function addBlock(type: BlockType): void {
    const block = createBlock(type);
    blocks.value.push(block);
    selectedId.value = block.id;

    if (addMenu.value) {
        addMenu.value.open = false;
    }
}

function duplicateBlock(block: Block): void {
    const copy: Block = { ...structuredClone(block), id: newBlockId() };
    const index = blocks.value.findIndex((item) => item.id === block.id);
    blocks.value.splice(index + 1, 0, copy);
    selectedId.value = copy.id;
}

function removeBlock(block: Block): void {
    const index = blocks.value.findIndex((item) => item.id === block.id);
    blocks.value.splice(index, 1);

    if (selectedId.value === block.id) {
        selectedId.value = blocks.value[Math.max(0, index - 1)]?.id ?? null;
    }
}

function moveBlock(index: number, direction: -1 | 1): void {
    const target = index + direction;

    if (target < 0 || target >= blocks.value.length) {
        return;
    }

    const [moved] = blocks.value.splice(index, 1);
    blocks.value.splice(target, 0, moved);
}

function onDrop(targetId: string): void {
    if (!draggingId.value || draggingId.value === targetId) {
        draggingId.value = null;

        return;
    }

    const from = blocks.value.findIndex((item) => item.id === draggingId.value);
    const to = blocks.value.findIndex((item) => item.id === targetId);

    if (from >= 0 && to >= 0) {
        const [moved] = blocks.value.splice(from, 1);
        blocks.value.splice(to, 0, moved);
    }

    draggingId.value = null;
}

function pickMedia(media: PickedMedia): void {
    if (pickerTarget.value === 'page') {
        pageIcon.value = media.url;

        return;
    }

    const block = selected.value;

    if (!block) {
        return;
    }

    block.src = media.url;
    block.media_id = media.id;

    if (!block.alt) {
        block.alt = media.alt ?? media.original_name;
    }
}

function addGridItem(block: Block): void {
    block.items = [
        ...(block.items ?? []),
        { title: 'Novo item', description: '' },
    ];
}

function removeGridItem(block: Block, index: number): void {
    block.items = (block.items ?? []).filter(
        (_, position) => position !== index,
    );
}

function publishNow(): void {
    if (!pageId.value) {
        return;
    }

    router.post(
        publish.url(pageId.value),
        { published_at: null },
        { preserveScroll: true },
    );
}

function schedulePublication(): void {
    if (!pageId.value || !scheduleAt.value) {
        return;
    }

    router.post(
        publish.url(pageId.value),
        { published_at: scheduleAt.value },
        {
            preserveScroll: true,
            onSuccess: () => {
                scheduleOpen.value = false;
            },
        },
    );
}

function unpublishPage(): void {
    if (!pageId.value) {
        return;
    }

    router.post(unpublish.url(pageId.value), {}, { preserveScroll: true });
}

function restoreRevision(revision: Revision): void {
    if (!pageId.value) {
        return;
    }

    router.post(
        restore.url({ page: pageId.value, revision: revision.id }),
        {},
        { preserveScroll: true },
    );
}

function onKeydown(event: KeyboardEvent): void {
    if ((event.metaKey || event.ctrlKey) && event.key.toLowerCase() === 's') {
        event.preventDefault();
        save();
    }
}

onMounted(() => window.addEventListener('keydown', onKeydown));
onBeforeUnmount(() => {
    window.removeEventListener('keydown', onKeydown);

    if (saveTimer) {
        clearTimeout(saveTimer);
    }
});
</script>

<template>
    <Head :title="pageId ? `Editar: ${title}` : 'Nova página'" />

    <div class="flex min-h-[calc(100vh-4rem)] flex-col">
        <!-- Barra de topo -->
        <header
            class="sticky top-0 z-20 flex flex-wrap items-center gap-3 border-b border-stone-200 bg-background px-4 py-3 dark:border-stone-800"
        >
            <Link
                :href="pagesIndex()"
                class="inline-flex items-center gap-1.5 rounded-sm px-2 py-1 text-sm text-stone-600 transition-colors hover:bg-stone-100 hover:text-stone-900 focus-visible:ring-2 focus-visible:ring-orange-600 focus-visible:outline-none dark:text-stone-400 dark:hover:bg-stone-800 dark:hover:text-stone-100"
            >
                <ArrowLeft class="size-4" aria-hidden="true" />
                Páginas
            </Link>

            <div class="min-w-0 flex-1">
                <input
                    v-model="title"
                    aria-label="Título da página"
                    class="w-full truncate rounded-sm bg-transparent px-2 py-1 text-lg font-semibold text-stone-900 outline-none focus-visible:ring-2 focus-visible:ring-orange-600 dark:text-stone-100"
                />
                <div class="mt-1 flex items-center gap-1 px-2">
                    <button
                        v-for="loc in pageLocales"
                        :key="loc"
                        type="button"
                        class="rounded-sm px-2 py-0.5 text-[11px] font-medium transition-colors"
                        :class="
                            editLocale === loc
                                ? 'bg-orange-100 text-orange-800 dark:bg-orange-600/20 dark:text-orange-300'
                                : 'text-stone-500 hover:bg-stone-100 dark:text-stone-500 dark:hover:bg-stone-800'
                        "
                        @click="switchPageLocale(loc)"
                    >
                        {{ localeLabels[loc] }}
                    </button>
                    <span
                        v-if="editLocale !== 'pt_BR'"
                        class="ml-1 text-[11px] text-stone-400"
                    >
                        Tradução — conteúdo vazio usa o português
                    </span>
                </div>
                <div class="flex items-center gap-2 px-2 text-xs">
                    <span
                        :class="[
                            'border px-2 py-0.5 font-medium',
                            statusClasses[status],
                        ]"
                    >
                        {{ statusLabels[status] }}
                    </span>
                    <span
                        v-if="isHome"
                        class="border border-orange-200 bg-orange-50 px-2 py-0.5 font-medium text-orange-800 dark:border-stone-700 dark:bg-orange-600/10 dark:text-orange-300"
                    >
                        Página inicial
                    </span>
                    <span
                        v-else-if="isNotFound"
                        class="border border-stone-200 bg-stone-100 px-2 py-0.5 font-medium text-stone-600 dark:border-stone-700 dark:bg-stone-800 dark:text-stone-300"
                    >
                        Página 404
                    </span>
                    <span
                        :class="
                            errorMessage
                                ? 'text-red-600 dark:text-red-400'
                                : 'text-stone-500 dark:text-stone-500'
                        "
                    >
                        {{ saveLabel }}
                    </span>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <button
                    type="button"
                    class="inline-flex h-9 items-center rounded-sm border border-stone-200 bg-white px-3 text-sm font-medium text-stone-700 transition-colors hover:bg-stone-50 disabled:opacity-50 dark:border-stone-800 dark:bg-stone-900 dark:text-stone-200 dark:hover:bg-stone-800"
                    :disabled="saving || !dirty"
                    @click="save"
                >
                    Salvar
                </button>

                <a
                    v-if="publicUrl"
                    :href="publicUrl"
                    target="_blank"
                    rel="noopener"
                    class="inline-flex h-9 items-center gap-1.5 rounded-sm border border-stone-200 bg-white px-3 text-sm font-medium text-stone-700 transition-colors hover:bg-stone-50 dark:border-stone-800 dark:bg-stone-900 dark:text-stone-200 dark:hover:bg-stone-800"
                >
                    <ExternalLink class="size-3.5" aria-hidden="true" />
                    Ver no site
                </a>

                <a
                    v-if="pageId"
                    :href="preview.url(pageId)"
                    target="_blank"
                    rel="noopener"
                    class="inline-flex h-9 items-center gap-1.5 rounded-sm border border-stone-200 bg-white px-3 text-sm font-medium text-stone-700 transition-colors hover:bg-stone-50 dark:border-stone-800 dark:bg-stone-900 dark:text-stone-200 dark:hover:bg-stone-800"
                >
                    <ExternalLink class="size-3.5" aria-hidden="true" />
                    Pré-visualizar
                </a>

                <button
                    v-if="status === 'published' && !isSystem"
                    type="button"
                    class="inline-flex h-9 items-center rounded-sm border border-stone-200 px-3 text-sm font-medium text-stone-700 transition-colors hover:bg-stone-50 dark:border-stone-800 dark:text-stone-200 dark:hover:bg-stone-800"
                    @click="unpublishPage"
                >
                    Despublicar
                </button>

                <button
                    type="button"
                    class="inline-flex h-9 items-center rounded-sm bg-stone-900 px-4 text-sm font-medium text-stone-50 dark:text-stone-900 dark:bg-stone-100 dark:hover:bg-stone-200 transition-colors hover:bg-stone-800 disabled:cursor-not-allowed disabled:opacity-50"
                    :disabled="!pageId"
                    :title="
                        pageId ? undefined : 'Salve a página antes de publicar'
                    "
                    @click="publishNow"
                >
                    {{ status === 'scheduled' ? 'Publicar agora' : 'Publicar' }}
                </button>

                <details
                    v-if="!isSystem"
                    class="relative"
                    :open="scheduleOpen"
                    @toggle="
                        scheduleOpen = ($event.target as HTMLDetailsElement)
                            .open
                    "
                >
                    <summary
                        class="inline-flex h-9 cursor-pointer items-center gap-1.5 rounded-sm border border-stone-200 px-3 text-sm font-medium text-stone-700 transition-colors marker:content-none hover:bg-stone-50 dark:border-stone-800 dark:text-stone-200 dark:hover:bg-stone-800"
                    >
                        <CalendarClock class="size-3.5" aria-hidden="true" />
                        Agendar
                    </summary>
                    <div
                        class="absolute right-0 z-30 mt-2 w-64 rounded-sm border border-stone-200 bg-white p-3 dark:border-stone-800 dark:bg-stone-900"
                    >
                        <label class="grid gap-1.5 text-xs font-medium">
                            <span class="text-stone-600 dark:text-stone-400">
                                Publicar em
                            </span>
                            <input
                                v-model="scheduleAt"
                                type="datetime-local"
                                class="h-10 w-full rounded-sm border border-stone-200 bg-white px-3 text-sm dark:border-stone-800 dark:bg-stone-950/60"
                            />
                        </label>
                        <button
                            type="button"
                            class="mt-3 w-full rounded-sm bg-stone-900 px-3 py-2 text-sm font-medium text-stone-50 dark:text-stone-900 dark:bg-stone-100 dark:hover:bg-stone-200 hover:bg-stone-800 disabled:opacity-50"
                            :disabled="!pageId || !scheduleAt"
                            @click="schedulePublication"
                        >
                            Agendar publicação
                        </button>
                    </div>
                </details>
            </div>
        </header>

        <div
            class="grid flex-1 grid-cols-1 gap-0 lg:grid-cols-[260px_1fr_320px]"
        >
            <!-- Seções -->
            <aside
                class="border-b border-stone-200 p-3 lg:border-r lg:border-b-0 dark:border-stone-800"
                aria-label="Seções da página"
            >
                <div class="flex items-center justify-between px-1">
                    <h2
                        class="text-[11px] font-semibold tracking-wider text-stone-400 uppercase dark:text-stone-500"
                    >
                        Seções
                    </h2>
                    <span
                        class="text-[11px] text-stone-400 dark:text-stone-500"
                    >
                        {{ blocks.length }}
                    </span>
                </div>

                <ul class="mt-2 space-y-1">
                    <li
                        v-for="(block, index) in blocks"
                        :key="block.id"
                        draggable="true"
                        class="group rounded-sm border px-2 py-1.5 transition-colors"
                        :class="
                            block.id === selectedId
                                ? 'border-orange-200 bg-orange-50 dark:border-stone-700 dark:bg-stone-800'
                                : 'border-transparent hover:bg-stone-100 dark:hover:bg-stone-800/60'
                        "
                        @dragstart="draggingId = block.id"
                        @dragover.prevent
                        @drop="onDrop(block.id)"
                        @click="selectedId = block.id"
                    >
                        <div class="flex items-center gap-1.5">
                            <GripVertical
                                class="size-3.5 shrink-0 cursor-grab text-stone-400"
                                aria-hidden="true"
                            />
                            <div class="min-w-0 flex-1">
                                <p
                                    class="truncate text-xs font-medium text-stone-700 dark:text-stone-300"
                                >
                                    {{ blockLabels[block.type] }}
                                </p>
                                <p
                                    class="truncate text-[11px] text-stone-500 dark:text-stone-500"
                                >
                                    {{
                                        block.hidden
                                            ? 'Oculto'
                                            : blockSummary(block)
                                    }}
                                </p>
                            </div>
                            <button
                                type="button"
                                class="rounded p-1 text-stone-400 hover:bg-white hover:text-stone-700 dark:hover:bg-stone-700 dark:hover:text-stone-100"
                                :aria-label="`Mover ${blockLabels[block.type]} para cima`"
                                @click.stop="moveBlock(index, -1)"
                            >
                                <ChevronUp
                                    class="size-3.5"
                                    aria-hidden="true"
                                />
                            </button>
                            <button
                                type="button"
                                class="rounded p-1 text-stone-400 hover:bg-white hover:text-stone-700 dark:hover:bg-stone-700 dark:hover:text-stone-100"
                                :aria-label="`Mover ${blockLabels[block.type]} para baixo`"
                                @click.stop="moveBlock(index, 1)"
                            >
                                <ChevronDown
                                    class="size-3.5"
                                    aria-hidden="true"
                                />
                            </button>
                        </div>
                    </li>
                </ul>

                <details ref="addMenu" class="relative mt-3">
                    <summary
                        class="inline-flex w-full cursor-pointer items-center justify-center gap-1.5 rounded-sm border border-dashed border-stone-300 px-3 py-2 text-sm font-medium text-stone-600 transition-colors marker:content-none hover:border-orange-400 hover:text-orange-700 dark:border-stone-700 dark:text-stone-400"
                    >
                        <Plus class="size-4" aria-hidden="true" />
                        Adicionar bloco
                    </summary>
                    <div
                        class="absolute left-0 z-30 mt-1 grid w-56 gap-1 rounded-sm border border-stone-200 bg-white p-2 dark:border-stone-800 dark:bg-stone-900"
                    >
                        <button
                            v-for="type in blockTypes"
                            :key="type"
                            type="button"
                            class="rounded-sm px-2 py-1.5 text-left text-sm text-stone-700 hover:bg-orange-50 hover:text-orange-800 dark:text-stone-300 dark:hover:bg-orange-950/40 dark:hover:text-orange-300"
                            @click="addBlock(type)"
                        >
                            {{ blockLabels[type] }}
                        </button>
                    </div>
                </details>

                <div
                    class="mt-4 grid gap-2 rounded-sm border border-stone-200 p-3 dark:border-stone-800"
                >
                    <label
                        class="flex items-center gap-2 text-xs font-medium text-stone-700 dark:text-stone-300"
                    >
                        <input v-model="isService" type="checkbox" />
                        Área de atuação
                    </label>
                    <p class="text-[11px] text-stone-400 dark:text-stone-500">
                        Aparece no bloco "Áreas de atuação" e no menu do site.
                    </p>
                    <div class="flex items-center gap-2">
                        <img
                            v-if="pageIcon"
                            :src="pageIcon"
                            alt=""
                            class="size-8 object-contain"
                        />
                        <button
                            type="button"
                            class="rounded-sm border border-stone-200 px-2 py-1 text-xs font-medium text-stone-600 hover:border-orange-400 hover:text-orange-700 dark:border-stone-800 dark:text-stone-300"
                            @click="
                                pickerTarget = 'page';
                                pickerOpen = true;
                            "
                        >
                            {{ pageIcon ? 'Trocar ícone' : 'Ícone da área' }}
                        </button>
                        <button
                            v-if="pageIcon"
                            type="button"
                            class="rounded-sm p-1 text-stone-400 hover:text-red-600"
                            aria-label="Remover ícone"
                            @click="pageIcon = ''"
                        >
                            <Trash2 class="size-3.5" aria-hidden="true" />
                        </button>
                    </div>
                </div>
            </aside>

            <!-- Canvas -->
            <main
                class="min-h-[50vh] bg-stone-50 p-4 sm:p-6 dark:bg-stone-950/40"
                @click.self="selectedId = null"
            >
                <div
                    class="mx-auto max-w-3xl overflow-hidden rounded-sm border border-stone-200 bg-white dark:border-stone-800 dark:bg-stone-900"
                >
                    <template v-if="blocks.length > 0">
                        <div
                            v-for="block in blocks"
                            :key="block.id"
                            class="relative cursor-pointer transition-shadow"
                            :class="
                                block.id === selectedId
                                    ? 'z-10 ring-2 ring-orange-600 ring-inset'
                                    : 'hover:ring-2 hover:ring-orange-200 hover:ring-inset dark:hover:ring-orange-600/30'
                            "
                            @click="selectedId = block.id"
                        >
                            <div
                                class="pointer-events-none opacity-100"
                                :class="{ 'opacity-40': block.hidden }"
                            >
                                <component
                                    :is="renderers[block.type]"
                                    :block="block"
                                />
                            </div>
                            <span
                                v-if="block.hidden"
                                class="absolute top-2 right-2 bg-stone-900/90 px-2 py-0.5 text-[10px] font-medium text-white"
                            >
                                Oculto
                            </span>
                        </div>
                    </template>

                    <div v-else class="p-8 text-center">
                        <p
                            class="text-sm font-medium text-stone-700 dark:text-stone-300"
                        >
                            Página em branco
                        </p>
                        <p
                            class="mt-1 text-xs text-stone-500 dark:text-stone-400"
                        >
                            Comece com um template ou adicione um bloco.
                        </p>
                        <div class="mt-5 grid gap-2 sm:grid-cols-3">
                            <button
                                v-for="template in templates"
                                :key="template.id"
                                type="button"
                                class="rounded-sm border border-stone-200 p-3 text-left transition-colors hover:border-orange-300 dark:border-stone-800 dark:hover:border-orange-600/40"
                                @click="applyTemplate(template.id)"
                            >
                                <span
                                    class="block text-sm font-medium text-stone-900 dark:text-stone-100"
                                >
                                    {{ template.label }}
                                </span>
                                <span
                                    class="mt-1 block text-xs text-stone-500 dark:text-stone-400"
                                >
                                    {{ template.description }}
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </main>

            <!-- Inspetor -->
            <aside
                class="border-t border-stone-200 p-4 lg:border-t-0 lg:border-l dark:border-stone-800"
                aria-label="Propriedades do bloco"
            >
                <template v-if="selected">
                    <div class="flex items-center justify-between">
                        <h2
                            class="text-[11px] font-semibold tracking-wider text-stone-400 uppercase dark:text-stone-500"
                        >
                            {{ blockLabels[selected.type] }}
                        </h2>
                        <div class="flex items-center gap-1">
                            <button
                                type="button"
                                class="rounded p-1 text-stone-400 hover:bg-stone-100 hover:text-stone-700 dark:hover:bg-stone-800"
                                :aria-label="
                                    selected.hidden
                                        ? 'Mostrar bloco'
                                        : 'Ocultar bloco'
                                "
                                @click="selected.hidden = !selected.hidden"
                            >
                                <component
                                    :is="selected.hidden ? EyeOff : Eye"
                                    class="size-4"
                                    aria-hidden="true"
                                />
                            </button>
                            <button
                                type="button"
                                class="rounded p-1 text-stone-400 hover:bg-stone-100 hover:text-stone-700 dark:hover:bg-stone-800"
                                aria-label="Duplicar bloco"
                                @click="duplicateBlock(selected)"
                            >
                                <Copy class="size-4" aria-hidden="true" />
                            </button>
                            <button
                                type="button"
                                class="rounded p-1 text-stone-400 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-500/10"
                                aria-label="Excluir bloco"
                                @click="removeBlock(selected)"
                            >
                                <Trash2 class="size-4" aria-hidden="true" />
                            </button>
                        </div>
                    </div>

                    <div class="mt-4 space-y-3">
                        <template v-if="selected.type === 'hero'">
                            <TextField
                                v-model="selected.title"
                                label="Título"
                            />
                            <TextField
                                v-model="selected.subtitle"
                                label="Subtítulo"
                                multiline
                                :rows="3"
                            />
                            <TextField
                                v-model="selected.button_label"
                                label="Botão"
                            />
                            <TextField
                                v-model="selected.button_href"
                                label="Link do botão"
                            />
                        </template>

                        <template v-else-if="selected.type === 'text'">
                            <TextField
                                v-model="selected.body"
                                label="Texto"
                                multiline
                                :rows="10"
                            />
                        </template>

                        <template v-else-if="selected.type === 'image'">
                            <button
                                type="button"
                                class="inline-flex w-full items-center justify-center gap-1.5 rounded-sm border border-stone-200 px-3 py-2 text-sm font-medium text-stone-700 hover:border-orange-400 hover:text-orange-700 dark:border-stone-800 dark:text-stone-200"
                                @click="
                                    pickerTarget = 'block';
                                    pickerOpen = true;
                                "
                            >
                                <ImageIcon class="size-4" aria-hidden="true" />
                                Escolher da biblioteca
                            </button>
                            <TextField
                                v-model="selected.src"
                                label="URL da imagem"
                            />
                            <TextField
                                v-model="selected.alt"
                                label="Texto alternativo"
                            />
                            <TextField
                                v-model="selected.caption"
                                label="Legenda"
                            />
                        </template>

                        <template v-else-if="selected.type === 'cta'">
                            <TextField
                                v-model="selected.title"
                                label="Título"
                            />
                            <TextField
                                v-model="selected.description"
                                label="Descrição"
                                multiline
                                :rows="3"
                            />
                            <TextField
                                v-model="selected.button_label"
                                label="Botão"
                            />
                            <TextField
                                v-model="selected.button_href"
                                label="Link do botão"
                            />
                        </template>

                        <template
                            v-else-if="
                                [
                                    'services',
                                    'testimonials',
                                    'faq',
                                    'clients',
                                ].includes(selected.type)
                            "
                        >
                            <TextField
                                v-model="selected.title"
                                label="Título da seção"
                            />
                            <p
                                class="text-xs text-stone-500 dark:text-stone-400"
                            >
                                O conteúdo desta seção vem do cadastro no
                                painel, não do editor.
                            </p>
                        </template>

                        <template v-else-if="selected.type === 'posts'">
                            <TextField
                                v-model="selected.title"
                                label="Título da seção"
                            />
                            <TextField
                                label="Quantidade de artigos"
                                type="number"
                                :model-value="String(selected.limit ?? 3)"
                                @update:model-value="
                                    selected.limit = Number($event) || 3
                                "
                            />
                        </template>

                        <template v-else-if="selected.type === 'products'">
                            <TextField
                                v-model="selected.title"
                                label="Título da seção"
                            />
                            <TextField
                                label="Quantidade de produtos"
                                type="number"
                                :model-value="String(selected.limit ?? 3)"
                                @update:model-value="
                                    selected.limit = Number($event) || 3
                                "
                            />
                        </template>

                        <template v-else-if="selected.type === 'categories'">
                            <TextField
                                v-model="selected.title"
                                label="Título da seção"
                            />
                            <TextField
                                v-model="selected.subtitle"
                                label="Subtítulo"
                            />
                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                Exibe até 4 categorias do catálogo, automáticas.
                            </p>
                        </template>

                        <template v-else-if="selected.type === 'contact'">
                            <TextField
                                v-model="selected.title"
                                label="Título"
                            />
                            <TextField
                                v-model="selected.subtitle"
                                label="Descrição"
                                multiline
                                :rows="2"
                            />
                        </template>

                        <template v-else-if="selected.type === 'career'">
                            <TextField
                                v-model="selected.title"
                                label="Título"
                            />
                            <TextField
                                v-model="selected.subtitle"
                                label="Descrição"
                                multiline
                                :rows="2"
                            />
                        </template>

                        <template v-else-if="selected.type === 'newsletter'">
                            <TextField
                                v-model="selected.title"
                                label="Título"
                            />
                            <TextField
                                v-model="selected.description"
                                label="Descrição"
                                multiline
                                :rows="2"
                            />
                        </template>

                        <template v-else-if="selected.type === 'grid'">
                            <div
                                v-for="(item, index) in selected.items ?? []"
                                :key="index"
                                class="space-y-2 rounded-sm border border-stone-200 p-3 dark:border-stone-800"
                            >
                                <div class="flex items-center justify-between">
                                    <span
                                        class="text-[11px] font-medium text-stone-400 uppercase"
                                    >
                                        Item {{ index + 1 }}
                                    </span>
                                    <button
                                        type="button"
                                        class="rounded p-1 text-stone-400 hover:text-red-600"
                                        aria-label="Remover item"
                                        @click="removeGridItem(selected, index)"
                                    >
                                        <Trash2
                                            class="size-3.5"
                                            aria-hidden="true"
                                        />
                                    </button>
                                </div>
                                <TextField
                                    v-model="item.title"
                                    label="Título"
                                />
                                <TextField
                                    v-model="item.description"
                                    label="Descrição"
                                    multiline
                                    :rows="2"
                                />
                            </div>
                            <button
                                type="button"
                                class="inline-flex w-full items-center justify-center gap-1.5 rounded-sm border border-dashed border-stone-300 px-3 py-2 text-sm text-stone-600 hover:border-orange-400 hover:text-orange-700 dark:border-stone-700 dark:text-stone-400"
                                @click="addGridItem(selected)"
                            >
                                <Plus class="size-4" aria-hidden="true" />
                                Adicionar item
                            </button>
                        </template>
                    </div>
                </template>

                <p v-else class="text-sm text-stone-500 dark:text-stone-400">
                    Selecione um bloco no canvas ou na lista para editar.
                </p>

                <details v-if="revisions.length > 0" class="mt-6">
                    <summary
                        class="cursor-pointer text-[11px] font-semibold tracking-wider text-stone-400 uppercase marker:content-none hover:text-stone-600 dark:text-stone-500"
                    >
                        Histórico ({{ revisions.length }})
                    </summary>
                    <ul class="mt-2 space-y-1">
                        <li
                            v-for="revision in revisions"
                            :key="revision.id"
                            class="flex items-center justify-between gap-2 rounded-sm border border-stone-200 px-2 py-1.5 text-xs dark:border-stone-800"
                        >
                            <span class="min-w-0">
                                <span
                                    class="block truncate text-stone-700 dark:text-stone-300"
                                >
                                    {{ revision.title }}
                                </span>
                                <span class="text-[11px] text-stone-400">
                                    {{ revision.author ?? 'Sistema' }}
                                </span>
                            </span>
                            <button
                                type="button"
                                class="shrink-0 rounded px-1.5 py-0.5 text-orange-700 hover:bg-orange-50 dark:text-orange-400 dark:hover:bg-orange-950/40"
                                @click="restoreRevision(revision)"
                            >
                                Restaurar
                            </button>
                        </li>
                    </ul>
                </details>
            </aside>
        </div>

        <MediaPicker
            :open="pickerOpen"
            @close="pickerOpen = false"
            @pick="pickMedia"
        />

        <p
            v-if="!pageId"
            class="px-4 pb-3 text-[11px] text-stone-400 dark:text-stone-500"
        >
            As alterações são salvas automaticamente. A página passa a existir
            no painel depois do primeiro salvamento.
        </p>
    </div>
</template>
