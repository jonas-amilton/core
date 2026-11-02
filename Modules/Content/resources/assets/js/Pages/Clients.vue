<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import { Check, Pencil, Plus, Trash2, X } from '@lucide/vue';
import { ref } from 'vue';
import MediaPicker from '@modules/Media/resources/assets/js/components/MediaPicker.vue';
import type { PickedMedia } from '@modules/Media/resources/assets/js/types';
import { destroy, index as clientIndex, store, update } from '@/routes/client';
import { fieldClasses } from '@/lib/formStyles';

type Client = {
    id: number;
    name: string;
    logo: string | null;
    url: string | null;
    sort_order: number;
};

const props = defineProps<{
    clients: Client[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Clientes',
                href: clientIndex(),
            },
        ],
    },
});

const editing = ref<Client | null>(null);
const pickerOpen = ref(false);

const form = useForm({
    name: '',
    logo: '',
    url: '',
    sort_order: 0,
});

function reset(): void {
    editing.value = null;
    form.reset();
    form.clearErrors();
}

function edit(item: Client): void {
    editing.value = item;
    form.name = item.name;
    form.logo = item.logo ?? '';
    form.url = item.url ?? '';
    form.sort_order = item.sort_order;
    form.clearErrors();
}

function submit(): void {
    if (editing.value) {
        form.patch(update.url(editing.value.id), {
            preserveScroll: true,
            onSuccess: reset,
        });

        return;
    }

    form.post(store.url(), { preserveScroll: true, onSuccess: reset });
}

function remove(item: Client): void {
    if (!window.confirm(`Excluir o cliente "${item.name}"?`)) {
        return;
    }

    router.delete(destroy.url(item.id));
}

function pickLogo(media: PickedMedia): void {
    form.logo = media.url;

    if (!form.name) {
        form.name = media.original_name;
    }
}
</script>

<template>
    <Head title="Clientes" />

    <div class="flex flex-1 flex-col gap-6 p-4 sm:p-6">
        <header>
            <h1
                class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-slate-100"
            >
                Clientes
            </h1>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
                Logos que aparecem no bloco "Clientes" das páginas.
            </p>
        </header>

        <form
            class="grid gap-3 rounded-xl border border-slate-200 bg-white p-4 dark:border-slate-800 dark:bg-slate-900"
            @submit.prevent="submit"
        >
            <div class="flex items-center justify-between">
                <h2
                    class="text-sm font-semibold text-slate-900 dark:text-slate-100"
                >
                    {{ editing ? 'Editar cliente' : 'Novo cliente' }}
                </h2>
                <button
                    v-if="editing"
                    type="button"
                    class="inline-flex items-center gap-1 text-xs text-slate-500 hover:text-slate-700 dark:text-slate-400"
                    @click="reset"
                >
                    <X class="size-3.5" aria-hidden="true" />
                    Cancelar edição
                </button>
            </div>

            <div class="grid gap-3 sm:grid-cols-2">
                <label class="grid gap-1.5">
                    <span
                        class="text-xs font-medium text-slate-600 dark:text-slate-400"
                    >
                        Nome
                    </span>
                    <input
                        v-model="form.name"
                        type="text"
                        :class="fieldClasses"
                    />
                    <span v-if="form.errors.name" class="text-xs text-red-600">
                        {{ form.errors.name }}
                    </span>
                </label>

                <label class="grid gap-1.5">
                    <span
                        class="text-xs font-medium text-slate-600 dark:text-slate-400"
                    >
                        Site (URL)
                    </span>
                    <input
                        v-model="form.url"
                        type="text"
                        :class="fieldClasses"
                    />
                </label>

                <div class="grid gap-1.5">
                    <span
                        class="text-xs font-medium text-slate-600 dark:text-slate-400"
                    >
                        Logo
                    </span>
                    <div class="flex items-center gap-2">
                        <img
                            v-if="form.logo"
                            :src="form.logo"
                            alt=""
                            class="h-10 max-w-24 object-contain"
                        />
                        <button
                            type="button"
                            class="h-10 rounded-lg border border-slate-200 px-3 text-sm font-medium text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:text-slate-200"
                            @click="pickerOpen = true"
                        >
                            Escolher logo
                        </button>
                    </div>
                </div>

                <label class="grid gap-1.5">
                    <span
                        class="text-xs font-medium text-slate-600 dark:text-slate-400"
                    >
                        Ordem
                    </span>
                    <input
                        v-model.number="form.sort_order"
                        type="number"
                        min="0"
                        :class="fieldClasses"
                    />
                </label>
            </div>

            <div>
                <button
                    type="submit"
                    class="inline-flex h-10 items-center gap-2 rounded-lg bg-indigo-600 px-4 text-sm font-medium text-white hover:bg-indigo-500 disabled:opacity-50"
                    :disabled="form.processing"
                >
                    <component
                        :is="editing ? Check : Plus"
                        class="size-4"
                        aria-hidden="true"
                    />
                    {{ editing ? 'Salvar alterações' : 'Adicionar' }}
                </button>
            </div>
        </form>

        <ul class="grid gap-2 sm:grid-cols-2">
            <li
                v-for="item in props.clients"
                :key="item.id"
                class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white p-3 dark:border-slate-800 dark:bg-slate-900"
            >
                <div
                    class="flex h-12 w-20 shrink-0 items-center justify-center"
                >
                    <img
                        v-if="item.logo"
                        :src="item.logo"
                        :alt="item.name"
                        class="max-h-12 max-w-20 object-contain"
                    />
                </div>
                <div class="min-w-0 flex-1">
                    <p
                        class="truncate text-sm font-medium text-slate-900 dark:text-slate-100"
                    >
                        {{ item.name }}
                    </p>
                    <p class="truncate text-xs text-slate-500">
                        {{ item.url || '—' }}
                    </p>
                </div>
                <div class="flex shrink-0 items-center gap-1">
                    <button
                        type="button"
                        class="rounded-md p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-slate-800"
                        aria-label="Editar cliente"
                        @click="edit(item)"
                    >
                        <Pencil class="size-3.5" aria-hidden="true" />
                    </button>
                    <button
                        type="button"
                        class="rounded-md p-1 text-slate-400 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-500/10"
                        aria-label="Excluir cliente"
                        @click="remove(item)"
                    >
                        <Trash2 class="size-3.5" aria-hidden="true" />
                    </button>
                </div>
            </li>
        </ul>

        <p
            v-if="props.clients.length === 0"
            class="rounded-xl border border-dashed border-slate-300 px-6 py-12 text-center text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400"
        >
            Nenhum cliente cadastrado.
        </p>

        <MediaPicker
            :open="pickerOpen"
            @close="pickerOpen = false"
            @pick="pickLogo"
        />
    </div>
</template>
