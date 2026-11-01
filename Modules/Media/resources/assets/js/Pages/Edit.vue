<script setup lang="ts">
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ArrowLeft, Trash2 } from '@lucide/vue';
import type { MediaItem } from '../types';
import { formatSize } from '../types';
import { fieldClasses } from '@/lib/formStyles';
import { destroy, index as mediaIndex, update } from '@/routes/media';

const props = defineProps<{
    media: MediaItem;
    usage: number;
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

const form = useForm({
    alt: props.media.alt ?? '',
    caption: props.media.caption ?? '',
});

function submit(): void {
    form.patch(update.url(props.media.id), { preserveScroll: true });
}

function remove(): void {
    if (
        !window.confirm(
            `Excluir "${props.media.original_name}"? O arquivo sai do disco e não pode ser recuperado.`,
        )
    ) {
        return;
    }

    router.delete(destroy.url(props.media.id));
}
</script>

<template>
    <Head title="Editar mídia" />

    <div class="flex flex-1 flex-col gap-6 p-4 sm:p-6">
        <header>
            <Link
                :href="mediaIndex()"
                class="inline-flex items-center gap-1.5 text-sm text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-100"
            >
                <ArrowLeft class="size-4" aria-hidden="true" />
                Mídia
            </Link>
            <h1
                class="mt-2 truncate text-2xl font-semibold tracking-tight text-slate-900 dark:text-slate-100"
            >
                {{ media.original_name }}
            </h1>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
                {{ formatSize(media.size) }}
                <template v-if="media.width && media.height">
                    · {{ media.width }}×{{ media.height }}
                </template>
                · {{ media.mime }}
            </p>
        </header>

        <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_320px]">
            <div
                class="flex items-center justify-center overflow-hidden rounded-xl border border-slate-200 bg-slate-100 dark:border-slate-800 dark:bg-slate-950/60"
            >
                <img
                    v-if="media.kind === 'image'"
                    :src="media.url"
                    :alt="media.alt ?? media.original_name"
                    class="max-h-[28rem] w-full object-contain"
                />
                <div v-else class="p-10 text-center">
                    <p class="text-sm text-slate-600 dark:text-slate-400">
                        Documento
                    </p>
                    <a
                        :href="media.url"
                        target="_blank"
                        rel="noopener"
                        class="mt-2 inline-block text-sm font-medium text-indigo-600 hover:underline dark:text-indigo-400"
                    >
                        Abrir arquivo
                    </a>
                </div>
            </div>

            <form class="space-y-4" @submit.prevent="submit">
                <div class="grid gap-2">
                    <label
                        for="alt"
                        class="text-xs font-medium text-slate-600 dark:text-slate-400"
                    >
                        Texto alternativo
                    </label>
                    <input
                        id="alt"
                        v-model="form.alt"
                        type="text"
                        :class="fieldClasses"
                    />
                    <p
                        v-if="form.errors.alt"
                        class="text-sm text-red-600 dark:text-red-400"
                    >
                        {{ form.errors.alt }}
                    </p>
                </div>

                <div class="grid gap-2">
                    <label
                        for="caption"
                        class="text-xs font-medium text-slate-600 dark:text-slate-400"
                    >
                        Legenda
                    </label>
                    <input
                        id="caption"
                        v-model="form.caption"
                        type="text"
                        :class="fieldClasses"
                    />
                    <p
                        v-if="form.errors.caption"
                        class="text-sm text-red-600 dark:text-red-400"
                    >
                        {{ form.errors.caption }}
                    </p>
                </div>

                <button
                    type="submit"
                    class="inline-flex h-11 w-full items-center justify-center rounded-lg bg-indigo-600 px-6 text-sm font-medium text-white transition-colors hover:bg-indigo-500 disabled:opacity-60"
                    :disabled="form.processing"
                >
                    {{ form.processing ? 'Salvando...' : 'Salvar' }}
                </button>

                <div
                    class="rounded-lg border border-slate-200 p-3 text-xs text-slate-600 dark:border-slate-800 dark:text-slate-400"
                >
                    <p v-if="usage > 0">
                        Usado em {{ usage }} página(s). Para excluir, remova o
                        arquivo dessas páginas primeiro.
                    </p>
                    <p v-else>Nenhuma página usa este arquivo.</p>
                </div>

                <button
                    type="button"
                    class="inline-flex h-10 w-full items-center justify-center gap-2 rounded-lg border border-red-200 px-4 text-sm font-medium text-red-600 transition-colors hover:bg-red-50 disabled:cursor-not-allowed disabled:opacity-50 dark:border-red-500/30 dark:text-red-400 dark:hover:bg-red-500/10"
                    :disabled="usage > 0"
                    @click="remove"
                >
                    <Trash2 class="size-4" aria-hidden="true" />
                    Excluir arquivo
                </button>
            </form>
        </div>
    </div>
</template>
