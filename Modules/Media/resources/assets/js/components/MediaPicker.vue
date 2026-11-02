<script setup lang="ts">
import { ref, watch } from 'vue';
import { ImageIcon, X } from '@lucide/vue';
import { picker } from '@/routes/media';
import type { PickedMedia } from '../types';

type ApiItem = PickedMedia;
export type { PickedMedia };

const props = defineProps<{
    open: boolean;
}>();

const emit = defineEmits<{
    close: [];
    pick: [media: PickedMedia];
}>();

const items = ref<ApiItem[]>([]);
const search = ref('');
const loading = ref(false);
const failed = ref(false);

async function load(): Promise<void> {
    loading.value = true;
    failed.value = false;

    try {
        const query = search.value
            ? `?search=${encodeURIComponent(search.value)}`
            : '';
        const response = await fetch(`${picker.url()}${query}`, {
            headers: { Accept: 'application/json' },
            credentials: 'same-origin',
        });

        if (!response.ok) {
            throw new Error(String(response.status));
        }

        const payload = (await response.json()) as { data: ApiItem[] };
        items.value = payload.data;
    } catch {
        failed.value = true;
    } finally {
        loading.value = false;
    }
}

watch(
    () => props.open,
    (open) => {
        if (open) {
            void load();
        }
    },
);

function choose(media: ApiItem): void {
    emit('pick', media);
    emit('close');
}
</script>

<template>
    <div
        v-if="open"
        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 p-4"
        role="dialog"
        aria-modal="true"
        aria-label="Escolher da biblioteca de mídia"
        @click.self="emit('close')"
    >
        <div
            class="flex max-h-[80vh] w-full max-w-3xl flex-col rounded-xl border border-slate-200 bg-white shadow-2xl dark:border-slate-800 dark:bg-slate-900"
        >
            <div
                class="flex items-center gap-3 border-b border-slate-200 p-4 dark:border-slate-800"
            >
                <h2
                    class="text-sm font-semibold text-slate-900 dark:text-slate-100"
                >
                    Biblioteca de mídia
                </h2>
                <button
                    type="button"
                    class="ml-auto rounded-md p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-slate-800"
                    aria-label="Fechar"
                    @click="emit('close')"
                >
                    <X class="size-4" aria-hidden="true" />
                </button>
            </div>

            <div
                class="flex gap-2 border-b border-slate-200 p-3 dark:border-slate-800"
            >
                <input
                    v-model="search"
                    type="search"
                    placeholder="Buscar imagem"
                    aria-label="Buscar imagem"
                    class="h-9 flex-1 rounded-lg border border-slate-200 bg-white px-3 text-sm text-slate-900 focus-visible:border-indigo-500 focus-visible:ring-2 focus-visible:ring-indigo-500/30 focus-visible:outline-none dark:border-slate-800 dark:bg-slate-950/60 dark:text-slate-100"
                    @keyup.enter="load"
                />
                <button
                    type="button"
                    class="h-9 rounded-lg border border-slate-200 px-3 text-sm font-medium text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:text-slate-200 dark:hover:bg-slate-800"
                    @click="load"
                >
                    Buscar
                </button>
            </div>

            <div class="min-h-40 flex-1 overflow-y-auto p-3">
                <p
                    v-if="loading"
                    class="p-6 text-center text-sm text-slate-500"
                >
                    Carregando...
                </p>
                <p
                    v-else-if="failed"
                    class="p-6 text-center text-sm text-red-600 dark:text-red-400"
                >
                    Não foi possível carregar a biblioteca.
                </p>
                <p
                    v-else-if="items.length === 0"
                    class="p-6 text-center text-sm text-slate-500 dark:text-slate-400"
                >
                    Nenhuma imagem encontrada.
                </p>
                <ul v-else class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                    <li v-for="item in items" :key="item.id">
                        <button
                            type="button"
                            class="group w-full overflow-hidden rounded-lg border border-slate-200 text-left transition-colors hover:border-indigo-400 dark:border-slate-800"
                            @click="choose(item)"
                        >
                            <img
                                :src="item.url"
                                :alt="item.alt ?? item.original_name"
                                class="aspect-video w-full object-cover"
                            />
                            <span
                                class="block truncate px-2 py-1.5 text-xs text-slate-600 dark:text-slate-400"
                            >
                                {{ item.alt || item.original_name }}
                            </span>
                        </button>
                    </li>
                </ul>
            </div>

            <div
                class="border-t border-slate-200 p-3 text-right dark:border-slate-800"
            >
                <a
                    href="/media/create"
                    target="_blank"
                    rel="noopener"
                    class="inline-flex items-center gap-1 text-xs font-medium text-indigo-600 hover:underline dark:text-indigo-400"
                >
                    <ImageIcon class="size-3.5" aria-hidden="true" />
                    Enviar novo arquivo
                </a>
            </div>
        </div>
    </div>
</template>
