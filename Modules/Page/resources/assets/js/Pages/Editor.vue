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
import GridBlock from '../Blocks/GridBlock.vue';
import HeroBlock from '../Blocks/HeroBlock.vue';
import ImageBlock from '../Blocks/ImageBlock.vue';
import TextBlock from '../Blocks/TextBlock.vue';
import {
    blockLabels,
    blockSummary,
    createBlock,
    newBlockId,
    statusClasses,
    statusLabels,
    templates,
} from '../blocks';
import MediaPicker from '../components/MediaPicker.vue';
import TextField from '../components/TextField.vue';
import type {
    Block,
    BlockType,
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
};

const blockTypes = Object.keys(blockLabels) as BlockType[];

const pageId = ref<number | null>(props.page?.id ?? null);
const title = ref(props.page?.title ?? 'Nova página');
const blocks = ref<Block[]>(props.page?.blocks ?? []);
const selectedId = ref<string | null>(blocks.value[0]?.id ?? null);

const dirty = ref(false);
const saving = ref(false);
const savedAt = ref<string | null>(null);
const errorMessage = ref<string | null>(null);
const scheduleOpen = ref(false);
const pickerOpen = ref(false);
const scheduleAt = ref('');
const draggingId = ref<string | null>(null);

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

function save(): void {
    if (saving.value) {
        schedule();

        return;
    }

    const payload = {
        title: title.value.trim() || 'Nova página',
        slug: props.page?.slug ?? null,
        blocks: blocks.value,
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
    [title, blocks],
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
            class="sticky top-0 z-20 flex flex-wrap items-center gap-3 border-b border-slate-200 bg-white/90 px-4 py-3 backdrop-blur-md dark:border-slate-800 dark:bg-slate-950/90"
        >
            <Link
                :href="pagesIndex()"
                class="inline-flex items-center gap-1.5 rounded-md px-2 py-1 text-sm text-slate-600 transition-colors hover:bg-slate-100 hover:text-slate-900 focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:outline-none dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-100"
            >
                <ArrowLeft class="size-4" aria-hidden="true" />
                Páginas
            </Link>

            <div class="min-w-0 flex-1">
                <input
                    v-model="title"
                    aria-label="Título da página"
                    class="w-full truncate rounded-md bg-transparent px-2 py-1 text-lg font-semibold text-slate-900 outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 dark:text-slate-100"
                />
                <div class="flex items-center gap-2 px-2 text-xs">
                    <span
                        :class="[
                            'rounded-full border px-2 py-0.5 font-medium',
                            statusClasses[status],
                        ]"
                    >
                        {{ statusLabels[status] }}
                    </span>
                    <span
                        v-if="isHome"
                        class="rounded-full border border-indigo-200 bg-indigo-50 px-2 py-0.5 font-medium text-indigo-700 dark:border-indigo-500/30 dark:bg-indigo-500/10 dark:text-indigo-300"
                    >
                        Página inicial
                    </span>
                    <span
                        v-else-if="isNotFound"
                        class="rounded-full border border-slate-200 bg-slate-100 px-2 py-0.5 font-medium text-slate-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300"
                    >
                        Página 404
                    </span>
                    <span
                        :class="
                            errorMessage
                                ? 'text-red-600 dark:text-red-400'
                                : 'text-slate-500 dark:text-slate-500'
                        "
                    >
                        {{ saveLabel }}
                    </span>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <button
                    type="button"
                    class="inline-flex h-9 items-center rounded-md border border-slate-200 bg-white px-3 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50 disabled:opacity-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800"
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
                    class="inline-flex h-9 items-center gap-1.5 rounded-md border border-slate-200 bg-white px-3 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800"
                >
                    <ExternalLink class="size-3.5" aria-hidden="true" />
                    Ver no site
                </a>

                <a
                    v-if="pageId"
                    :href="preview.url(pageId)"
                    target="_blank"
                    rel="noopener"
                    class="inline-flex h-9 items-center gap-1.5 rounded-md border border-slate-200 bg-white px-3 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800"
                >
                    <ExternalLink class="size-3.5" aria-hidden="true" />
                    Pré-visualizar
                </a>

                <button
                    v-if="status === 'published' && !isSystem"
                    type="button"
                    class="inline-flex h-9 items-center rounded-md border border-slate-200 px-3 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50 dark:border-slate-800 dark:text-slate-200 dark:hover:bg-slate-800"
                    @click="unpublishPage"
                >
                    Despublicar
                </button>

                <button
                    type="button"
                    class="inline-flex h-9 items-center rounded-md bg-indigo-600 px-4 text-sm font-medium text-white transition-colors hover:bg-indigo-500 disabled:cursor-not-allowed disabled:opacity-50"
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
                        class="inline-flex h-9 cursor-pointer items-center gap-1.5 rounded-md border border-slate-200 px-3 text-sm font-medium text-slate-700 transition-colors marker:content-none hover:bg-slate-50 dark:border-slate-800 dark:text-slate-200 dark:hover:bg-slate-800"
                    >
                        <CalendarClock class="size-3.5" aria-hidden="true" />
                        Agendar
                    </summary>
                    <div
                        class="absolute right-0 z-30 mt-2 w-64 rounded-xl border border-slate-200 bg-white p-3 shadow-xl dark:border-slate-800 dark:bg-slate-900"
                    >
                        <label class="grid gap-1.5 text-xs font-medium">
                            <span class="text-slate-600 dark:text-slate-400">
                                Publicar em
                            </span>
                            <input
                                v-model="scheduleAt"
                                type="datetime-local"
                                class="h-10 w-full rounded-lg border border-slate-200 bg-white px-3 text-sm dark:border-slate-800 dark:bg-slate-950/60"
                            />
                        </label>
                        <button
                            type="button"
                            class="mt-3 w-full rounded-lg bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-500 disabled:opacity-50"
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
                class="border-b border-slate-200 p-3 lg:border-r lg:border-b-0 dark:border-slate-800"
                aria-label="Seções da página"
            >
                <div class="flex items-center justify-between px-1">
                    <h2
                        class="text-[11px] font-semibold tracking-wider text-slate-400 uppercase dark:text-slate-500"
                    >
                        Seções
                    </h2>
                    <span
                        class="text-[11px] text-slate-400 dark:text-slate-500"
                    >
                        {{ blocks.length }}
                    </span>
                </div>

                <ul class="mt-2 space-y-1">
                    <li
                        v-for="(block, index) in blocks"
                        :key="block.id"
                        draggable="true"
                        class="group rounded-lg border px-2 py-1.5 transition-colors"
                        :class="
                            block.id === selectedId
                                ? 'border-indigo-200 bg-indigo-50 dark:border-indigo-500/30 dark:bg-indigo-950/40'
                                : 'border-transparent hover:bg-slate-100 dark:hover:bg-slate-800/60'
                        "
                        @dragstart="draggingId = block.id"
                        @dragover.prevent
                        @drop="onDrop(block.id)"
                        @click="selectedId = block.id"
                    >
                        <div class="flex items-center gap-1.5">
                            <GripVertical
                                class="size-3.5 shrink-0 cursor-grab text-slate-400"
                                aria-hidden="true"
                            />
                            <div class="min-w-0 flex-1">
                                <p
                                    class="truncate text-xs font-medium text-slate-700 dark:text-slate-300"
                                >
                                    {{ blockLabels[block.type] }}
                                </p>
                                <p
                                    class="truncate text-[11px] text-slate-500 dark:text-slate-500"
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
                                class="rounded p-1 text-slate-400 hover:bg-white hover:text-slate-700 dark:hover:bg-slate-700 dark:hover:text-slate-100"
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
                                class="rounded p-1 text-slate-400 hover:bg-white hover:text-slate-700 dark:hover:bg-slate-700 dark:hover:text-slate-100"
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
                        class="inline-flex w-full cursor-pointer items-center justify-center gap-1.5 rounded-lg border border-dashed border-slate-300 px-3 py-2 text-sm font-medium text-slate-600 transition-colors marker:content-none hover:border-indigo-400 hover:text-indigo-600 dark:border-slate-700 dark:text-slate-400"
                    >
                        <Plus class="size-4" aria-hidden="true" />
                        Adicionar bloco
                    </summary>
                    <div
                        class="absolute left-0 z-30 mt-1 grid w-56 gap-1 rounded-xl border border-slate-200 bg-white p-2 shadow-xl dark:border-slate-800 dark:bg-slate-900"
                    >
                        <button
                            v-for="type in blockTypes"
                            :key="type"
                            type="button"
                            class="rounded-md px-2 py-1.5 text-left text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 dark:text-slate-300 dark:hover:bg-indigo-950/40 dark:hover:text-indigo-300"
                            @click="addBlock(type)"
                        >
                            {{ blockLabels[type] }}
                        </button>
                    </div>
                </details>
            </aside>

            <!-- Canvas -->
            <main
                class="min-h-[50vh] bg-slate-50 p-4 sm:p-6 dark:bg-slate-950/40"
                @click.self="selectedId = null"
            >
                <div
                    class="mx-auto max-w-3xl overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900"
                >
                    <template v-if="blocks.length > 0">
                        <div
                            v-for="block in blocks"
                            :key="block.id"
                            class="relative cursor-pointer transition-shadow"
                            :class="
                                block.id === selectedId
                                    ? 'z-10 ring-2 ring-indigo-500 ring-inset'
                                    : 'hover:ring-2 hover:ring-indigo-200 hover:ring-inset dark:hover:ring-indigo-500/30'
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
                                class="absolute top-2 right-2 rounded-full bg-slate-900/80 px-2 py-0.5 text-[10px] font-medium text-white"
                            >
                                Oculto
                            </span>
                        </div>
                    </template>

                    <div v-else class="p-8 text-center">
                        <p
                            class="text-sm font-medium text-slate-700 dark:text-slate-300"
                        >
                            Página em branco
                        </p>
                        <p
                            class="mt-1 text-xs text-slate-500 dark:text-slate-400"
                        >
                            Comece com um template ou adicione um bloco.
                        </p>
                        <div class="mt-5 grid gap-2 sm:grid-cols-3">
                            <button
                                v-for="template in templates"
                                :key="template.id"
                                type="button"
                                class="rounded-lg border border-slate-200 p-3 text-left transition-colors hover:border-indigo-300 dark:border-slate-800 dark:hover:border-indigo-500/40"
                                @click="applyTemplate(template.id)"
                            >
                                <span
                                    class="block text-sm font-medium text-slate-900 dark:text-slate-100"
                                >
                                    {{ template.label }}
                                </span>
                                <span
                                    class="mt-1 block text-xs text-slate-500 dark:text-slate-400"
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
                class="border-t border-slate-200 p-4 lg:border-t-0 lg:border-l dark:border-slate-800"
                aria-label="Propriedades do bloco"
            >
                <template v-if="selected">
                    <div class="flex items-center justify-between">
                        <h2
                            class="text-[11px] font-semibold tracking-wider text-slate-400 uppercase dark:text-slate-500"
                        >
                            {{ blockLabels[selected.type] }}
                        </h2>
                        <div class="flex items-center gap-1">
                            <button
                                type="button"
                                class="rounded p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-slate-800"
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
                                class="rounded p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-slate-800"
                                aria-label="Duplicar bloco"
                                @click="duplicateBlock(selected)"
                            >
                                <Copy class="size-4" aria-hidden="true" />
                            </button>
                            <button
                                type="button"
                                class="rounded p-1 text-slate-400 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-500/10"
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
                                class="inline-flex w-full items-center justify-center gap-1.5 rounded-lg border border-slate-200 px-3 py-2 text-sm font-medium text-slate-700 hover:border-indigo-400 hover:text-indigo-600 dark:border-slate-800 dark:text-slate-200"
                                @click="pickerOpen = true"
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

                        <template v-else-if="selected.type === 'grid'">
                            <div
                                v-for="(item, index) in selected.items ?? []"
                                :key="index"
                                class="space-y-2 rounded-lg border border-slate-200 p-3 dark:border-slate-800"
                            >
                                <div class="flex items-center justify-between">
                                    <span
                                        class="text-[11px] font-medium text-slate-400 uppercase"
                                    >
                                        Item {{ index + 1 }}
                                    </span>
                                    <button
                                        type="button"
                                        class="rounded p-1 text-slate-400 hover:text-red-600"
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
                                class="inline-flex w-full items-center justify-center gap-1.5 rounded-lg border border-dashed border-slate-300 px-3 py-2 text-sm text-slate-600 hover:border-indigo-400 hover:text-indigo-600 dark:border-slate-700 dark:text-slate-400"
                                @click="addGridItem(selected)"
                            >
                                <Plus class="size-4" aria-hidden="true" />
                                Adicionar item
                            </button>
                        </template>
                    </div>
                </template>

                <p v-else class="text-sm text-slate-500 dark:text-slate-400">
                    Selecione um bloco no canvas ou na lista para editar.
                </p>

                <details v-if="revisions.length > 0" class="mt-6">
                    <summary
                        class="cursor-pointer text-[11px] font-semibold tracking-wider text-slate-400 uppercase marker:content-none hover:text-slate-600 dark:text-slate-500"
                    >
                        Histórico ({{ revisions.length }})
                    </summary>
                    <ul class="mt-2 space-y-1">
                        <li
                            v-for="revision in revisions"
                            :key="revision.id"
                            class="flex items-center justify-between gap-2 rounded-md border border-slate-200 px-2 py-1.5 text-xs dark:border-slate-800"
                        >
                            <span class="min-w-0">
                                <span
                                    class="block truncate text-slate-700 dark:text-slate-300"
                                >
                                    {{ revision.title }}
                                </span>
                                <span class="text-[11px] text-slate-400">
                                    {{ revision.author ?? 'Sistema' }}
                                </span>
                            </span>
                            <button
                                type="button"
                                class="shrink-0 rounded px-1.5 py-0.5 text-indigo-600 hover:bg-indigo-50 dark:text-indigo-400 dark:hover:bg-indigo-950/40"
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
            class="px-4 pb-3 text-[11px] text-slate-400 dark:text-slate-500"
        >
            As alterações são salvas automaticamente. A página passa a existir
            no painel depois do primeiro salvamento.
        </p>
    </div>
</template>
