<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import { Check, Pencil, Plus, Trash2, X } from '@lucide/vue';
import { computed, ref } from 'vue';
import {
    destroy,
    index as storesIndex,
    store,
    update,
} from '@/routes/stores';
import { fieldClasses } from '@/lib/formStyles';

type StoreItem = {
    id: number;
    name: string;
    kind: string;
    kind_label: string;
    address: string | null;
    phone: string | null;
    url: string | null;
    position: number;
    translations: Record<
        'en' | 'es',
        { name: string | null; address: string | null }
    >;
};

type KindOption = {
    value: string;
    label: string;
};

const props = defineProps<{
    stores: StoreItem[];
    kinds: KindOption[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Lojas', href: storesIndex() }],
    },
});

const editing = ref<StoreItem | null>(null);

type EditLocale = 'pt_BR' | 'en' | 'es';

const locales: EditLocale[] = ['pt_BR', 'en', 'es'];
const localeLabels: Record<EditLocale, string> = {
    pt_BR: 'PT',
    en: 'EN',
    es: 'ES',
};
const editLocale = ref<EditLocale>('pt_BR');

const form = useForm({
    name: '',
    kind: 'physical',
    address: '',
    phone: '',
    url: '',
    position: 0,
    translations: {
        en: { name: '', address: '' },
        es: { name: '', address: '' },
    },
});

function translatedField(field: 'name' | 'address') {
    return computed<string>({
        get: () =>
            editLocale.value === 'pt_BR'
                ? form[field]
                : (form.translations[editLocale.value as 'en' | 'es'][field] ??
                  ''),
        set: (value: string) => {
            if (editLocale.value === 'pt_BR') {
                form[field] = value;

                return;
            }

            form.translations[editLocale.value as 'en' | 'es'][field] = value;
        },
    });
}

const name = translatedField('name');
const address = translatedField('address');

function reset(): void {
    editing.value = null;
    form.reset();
    form.clearErrors();
}

function edit(item: StoreItem): void {
    editing.value = item;
    editLocale.value = 'pt_BR';
    form.name = item.name;
    form.kind = item.kind;
    form.address = item.address ?? '';
    form.phone = item.phone ?? '';
    form.url = item.url ?? '';
    form.position = item.position;
    form.translations.en.name = item.translations?.en?.name ?? '';
    form.translations.en.address = item.translations?.en?.address ?? '';
    form.translations.es.name = item.translations?.es?.name ?? '';
    form.translations.es.address = item.translations?.es?.address ?? '';
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

function remove(item: StoreItem): void {
    if (!window.confirm(`Excluir a loja "${item.name}"?`)) {
        return;
    }

    router.delete(destroy.url(item.id));
}
</script>

<template>
    <Head title="Lojas" />

    <div class="flex flex-1 flex-col gap-6 p-4 sm:p-6">
        <header>
            <h1
                class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-slate-100"
            >
                Lojas
            </h1>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
                Lojas físicas e virtuais exibidas em /onde-comprar.
            </p>
        </header>

        <form
            class="grid gap-3 rounded-xl border border-slate-200 bg-white p-4 sm:grid-cols-2 dark:border-slate-800 dark:bg-slate-900"
            @submit.prevent="submit"
        >
            <div class="flex items-center justify-between sm:col-span-2">
                <h2 class="text-sm font-semibold text-slate-900 dark:text-slate-100">
                    {{ editing ? 'Editar loja' : 'Nova loja' }}
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

            <div class="flex items-center gap-1 sm:col-span-2">
                <button
                    v-for="loc in locales"
                    :key="loc"
                    type="button"
                    class="rounded-md px-2 py-0.5 text-[11px] font-medium transition-colors"
                    :class="
                        editLocale === loc
                            ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-300'
                            : 'text-slate-500 hover:bg-slate-100 dark:text-slate-500 dark:hover:bg-slate-800'
                    "
                    @click="editLocale = loc"
                >
                    {{ localeLabels[loc] }}
                </button>
                <span
                    v-if="editLocale !== 'pt_BR'"
                    class="ml-1 text-[11px] text-slate-400"
                >
                    Tradução — vazio usa o português
                </span>
            </div>

            <label class="grid gap-1.5">
                <span class="text-xs font-medium text-slate-600 dark:text-slate-400">
                    Nome
                </span>
                <input v-model="name" type="text" :class="fieldClasses" />
                <span v-if="form.errors.name" class="text-xs text-red-600">
                    {{ form.errors.name }}
                </span>
            </label>

            <label class="grid gap-1.5">
                <span class="text-xs font-medium text-slate-600 dark:text-slate-400">
                    Tipo
                </span>
                <select v-model="form.kind" :class="fieldClasses">
                    <option
                        v-for="kind in props.kinds"
                        :key="kind.value"
                        :value="kind.value"
                    >
                        {{ kind.label }}
                    </option>
                </select>
            </label>

            <label class="grid gap-1.5 sm:col-span-2">
                <span class="text-xs font-medium text-slate-600 dark:text-slate-400">
                    Endereço
                </span>
                <input v-model="address" type="text" :class="fieldClasses" />
            </label>

            <label class="grid gap-1.5">
                <span class="text-xs font-medium text-slate-600 dark:text-slate-400">
                    Telefone
                </span>
                <input v-model="form.phone" type="text" :class="fieldClasses" />
            </label>

            <label class="grid gap-1.5">
                <span class="text-xs font-medium text-slate-600 dark:text-slate-400">
                    Site (URL)
                </span>
                <input v-model="form.url" type="text" :class="fieldClasses" />
            </label>

            <label class="grid gap-1.5 sm:max-w-40">
                <span class="text-xs font-medium text-slate-600 dark:text-slate-400">
                    Ordem
                </span>
                <input
                    v-model.number="form.position"
                    type="number"
                    min="0"
                    :class="fieldClasses"
                />
            </label>

            <div class="flex items-end">
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
                v-for="item in props.stores"
                :key="item.id"
                class="rounded-xl border border-slate-200 bg-white p-4 dark:border-slate-800 dark:bg-slate-900"
            >
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-slate-900 dark:text-slate-100">
                            {{ item.name }}
                            <span class="text-xs font-normal text-slate-400">
                                · {{ item.kind_label }}
                            </span>
                        </p>
                        <p class="mt-1 text-xs text-slate-500">
                            {{ item.address || item.url || '—' }}
                        </p>
                    </div>
                    <div class="flex shrink-0 items-start gap-1">
                        <button
                            type="button"
                            class="rounded-md p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-slate-800"
                            aria-label="Editar loja"
                            @click="edit(item)"
                        >
                            <Pencil class="size-3.5" aria-hidden="true" />
                        </button>
                        <button
                            type="button"
                            class="rounded-md p-1 text-slate-400 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-500/10"
                            aria-label="Excluir loja"
                            @click="remove(item)"
                        >
                            <Trash2 class="size-3.5" aria-hidden="true" />
                        </button>
                    </div>
                </div>
            </li>
        </ul>

        <p
            v-if="props.stores.length === 0"
            class="rounded-xl border border-dashed border-slate-300 px-6 py-12 text-center text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400"
        >
            Nenhuma loja cadastrada.
        </p>
    </div>
</template>
