<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import {
    Copy,
    FileText,
    Image as ImageIcon,
    Pencil,
    Plus,
    Trash2,
} from '@lucide/vue';
import { ref } from 'vue';
import { formatSize } from '../types';
import type { MediaFilters, MediaItem, MediaKindOption } from '../types';
import { create, destroy, edit, index as mediaIndex } from '@/routes/media';

const props = defineProps<{
    media: MediaItem[];
    filters: MediaFilters;
    kinds: MediaKindOption[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Mídia',
                href: mediaIndex(),
            },
        ],
    },
});

const search = ref(props.filters.search);
const copied = ref<number | null>(null);

function applyFilters(kind: string | null = props.filters.kind): void {
    router.get(
        mediaIndex().url,
        {
            search: search.value || undefined,
            kind: kind ?? undefined,
        },
        { preserveState: true, preserveScroll: true },
    );
}

function copyUrl(item: MediaItem): void {
    void navigator.clipboard?.writeText(item.url);
    copied.value = item.id;
    window.setTimeout(() => {
        copied.value = null;
    }, 1500);
}

function remove(item: MediaItem): void {
    if (
        !window.confirm(
            `Excluir "${item.original_name}"? O arquivo sai do disco e não pode ser recuperado.`,
        )
    ) {
        return;
    }

    router.delete(destroy.url(item.id));
}
</script>

<template>
    <Head title="Mídia" />

    <div class="flex flex-1 flex-col gap-6 p-4 sm:p-6">
        <header class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <h1
                    class="text-2xl font-semibold tracking-tight text-stone-900 dark:text-stone-100"
                >
                    Mídia
                </h1>
                <p class="mt-1 text-sm text-stone-600 dark:text-stone-400">
                    Imagens e documentos usados nas páginas do site.
                </p>
            </div>
            <Link
                :href="create()"
                class="inline-flex h-10 items-center gap-2 rounded-sm bg-stone-900 px-4 text-sm font-medium text-stone-50 dark:text-stone-900 dark:bg-stone-100 dark:hover:bg-stone-200 transition-colors hover:bg-stone-800"
            >
                <Plus class="size-4" aria-hidden="true" />
                Enviar arquivo
            </Link>
        </header>

        <div class="flex flex-wrap items-center gap-3">
            <form class="flex flex-1 gap-2" @submit.prevent="applyFilters()">
                <input
                    v-model="search"
                    type="search"
                    placeholder="Buscar por nome ou descrição"
                    aria-label="Buscar mídia"
                    class="h-10 w-full max-w-sm rounded-sm border border-stone-200 bg-white px-3 text-sm text-stone-900 placeholder:text-stone-400 focus-visible:border-orange-600 focus-visible:ring-2 focus-visible:ring-orange-600/30 focus-visible:outline-none dark:border-stone-800 dark:bg-stone-950/60 dark:text-stone-100"
                />
                <button
                    type="submit"
                    class="h-10 rounded-sm border border-stone-200 px-3 text-sm font-medium text-stone-700 hover:bg-stone-50 dark:border-stone-800 dark:text-stone-200 dark:hover:bg-stone-800"
                >
                    Buscar
                </button>
            </form>

            <div class="flex flex-wrap gap-1">
                <button
                    type="button"
                    :class="[
                        'border px-3 py-1 text-xs font-medium transition-colors',
                        filters.kind === null
                            ? 'border-orange-200 bg-orange-50 text-orange-800 dark:border-stone-700 dark:bg-orange-600/10 dark:text-orange-300'
                            : 'border-stone-200 text-stone-600 hover:bg-stone-50 dark:border-stone-800 dark:text-stone-400 dark:hover:bg-stone-800',
                    ]"
                    @click="applyFilters(null)"
                >
                    Todos
                </button>
                <button
                    v-for="kind in kinds"
                    :key="kind.value"
                    type="button"
                    :class="[
                        'border px-3 py-1 text-xs font-medium transition-colors',
                        filters.kind === kind.value
                            ? 'border-orange-200 bg-orange-50 text-orange-800 dark:border-stone-700 dark:bg-orange-600/10 dark:text-orange-300'
                            : 'border-stone-200 text-stone-600 hover:bg-stone-50 dark:border-stone-800 dark:text-stone-400 dark:hover:bg-stone-800',
                    ]"
                    @click="applyFilters(kind.value)"
                >
                    {{ kind.label }}
                </button>
            </div>
        </div>

        <ul
            v-if="media.length > 0"
            class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"
        >
            <li
                v-for="item in media"
                :key="item.id"
                class="overflow-hidden rounded-sm border border-stone-200 bg-white dark:border-stone-800 dark:bg-stone-900"
            >
                <div
                    class="flex aspect-video items-center justify-center bg-stone-100 dark:bg-stone-950/60"
                >
                    <img
                        v-if="item.kind === 'image'"
                        :src="item.url"
                        :alt="item.alt ?? item.original_name"
                        class="h-full w-full object-cover"
                    />
                    <FileText
                        v-else
                        class="size-8 text-stone-400 dark:text-stone-500"
                        aria-hidden="true"
                    />
                </div>

                <div class="space-y-1 p-3">
                    <p
                        class="truncate text-sm font-medium text-stone-900 dark:text-stone-100"
                        :title="item.original_name"
                    >
                        {{ item.original_name }}
                    </p>
                    <p class="text-xs text-stone-500 dark:text-stone-400">
                        {{ formatSize(item.size) }}
                        <template v-if="item.width && item.height">
                            · {{ item.width }}×{{ item.height }}
                        </template>
                    </p>
                    <div class="flex items-center gap-1 pt-1">
                        <Link
                            :href="edit(item.id)"
                            class="inline-flex items-center gap-1 rounded-sm px-2 py-1 text-xs font-medium text-orange-700 hover:bg-orange-50 dark:text-orange-400 dark:hover:bg-orange-950/40"
                        >
                            <Pencil class="size-3" aria-hidden="true" />
                            Editar
                        </Link>
                        <button
                            type="button"
                            class="inline-flex items-center gap-1 rounded-sm px-2 py-1 text-xs text-stone-500 hover:bg-stone-100 dark:text-stone-400 dark:hover:bg-stone-800"
                            @click="copyUrl(item)"
                        >
                            <Copy class="size-3" aria-hidden="true" />
                            {{ copied === item.id ? 'Copiado' : 'URL' }}
                        </button>
                        <button
                            type="button"
                            class="ml-auto rounded-sm p-1 text-stone-400 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-500/10"
                            :aria-label="`Excluir ${item.original_name}`"
                            @click="remove(item)"
                        >
                            <Trash2 class="size-3.5" aria-hidden="true" />
                        </button>
                    </div>
                </div>
            </li>
        </ul>

        <div
            v-else
            class="flex flex-col items-center justify-center rounded-sm border border-dashed border-stone-300 bg-white px-6 py-16 text-center dark:border-stone-700 dark:bg-stone-900"
        >
            <span
                class="flex size-12 items-center justify-center rounded-sm bg-orange-50 text-orange-700 dark:bg-orange-600/10 dark:text-orange-400"
            >
                <ImageIcon class="size-6" aria-hidden="true" />
            </span>
            <p
                class="mt-4 text-sm font-medium text-stone-900 dark:text-stone-100"
            >
                Nenhum arquivo ainda
            </p>
            <p class="mt-1 max-w-sm text-sm text-stone-600 dark:text-stone-400">
                Envie imagens (JPG, PNG, WebP, GIF) ou PDFs de até 10 MB.
            </p>
            <Link
                :href="create()"
                class="mt-5 inline-flex h-10 items-center gap-2 rounded-sm bg-stone-900 px-4 text-sm font-medium text-stone-50 dark:text-stone-900 dark:bg-stone-100 dark:hover:bg-stone-200 transition-colors hover:bg-stone-800"
            >
                <Plus class="size-4" aria-hidden="true" />
                Enviar primeiro arquivo
            </Link>
        </div>
    </div>
</template>
