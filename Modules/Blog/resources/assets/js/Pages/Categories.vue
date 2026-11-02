<script setup lang="ts">
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ArrowLeft, Check, Pencil, Plus, Trash2, X } from '@lucide/vue';
import { computed, ref } from 'vue';
import { index as blogIndex } from '@/routes/blog';
import {
    destroy,
    index as categoriesIndex,
    store,
    update,
} from '@/routes/blog/category';
import { fieldClasses } from '@/lib/formStyles';

type Category = {
    id: number;
    name: string;
    slug: string;
    translations: Record<'en' | 'es', { name: string | null }>;
};

const props = defineProps<{
    categories: Category[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Blog',
                href: blogIndex(),
            },
            {
                title: 'Categorias',
                href: categoriesIndex(),
            },
        ],
    },
});

const editing = ref<Category | null>(null);

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
    slug: '',
    translations: {
        en: { name: '' },
        es: { name: '' },
    },
});

const name = computed({
    get: () =>
        editLocale.value === 'pt_BR'
            ? form.name
            : (form.translations[editLocale.value as 'en' | 'es'].name ?? ''),
    set: (value: string) => {
        if (editLocale.value === 'pt_BR') {
            form.name = value;

            return;
        }

        form.translations[editLocale.value as 'en' | 'es'].name = value;
    },
});

function reset(): void {
    editing.value = null;
    form.reset();
    form.clearErrors();
}

function edit(item: Category): void {
    editing.value = item;
    editLocale.value = 'pt_BR';
    form.name = item.name;
    form.slug = item.slug;
    form.translations.en.name = item.translations?.en?.name ?? '';
    form.translations.es.name = item.translations?.es?.name ?? '';
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

function remove(item: Category): void {
    if (!window.confirm(`Excluir a categoria "${item.name}"?`)) {
        return;
    }

    router.delete(destroy.url(item.id));
}
</script>

<template>
    <Head title="Categorias do blog" />

    <div class="flex flex-1 flex-col gap-6 p-4 sm:p-6">
        <header>
            <Link
                :href="blogIndex()"
                class="inline-flex items-center gap-1.5 rounded-md px-2 py-1 text-sm text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-100"
            >
                <ArrowLeft class="size-4" aria-hidden="true" />
                Blog
            </Link>
            <h1
                class="mt-2 text-2xl font-semibold tracking-tight text-slate-900 dark:text-slate-100"
            >
                Categorias
            </h1>
        </header>

        <form
            class="grid gap-3 rounded-xl border border-slate-200 bg-white p-4 sm:grid-cols-[1fr_1fr_auto] dark:border-slate-800 dark:bg-slate-900"
            @submit.prevent="submit"
        >
            <label class="grid gap-1.5">
                <span
                    class="flex items-center gap-1 text-xs font-medium text-slate-600 dark:text-slate-400"
                >
                    Nome
                    <button
                        v-for="loc in locales"
                        :key="loc"
                        type="button"
                        class="rounded px-1 text-[10px]"
                        :class="
                            editLocale === loc
                                ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-300'
                                : 'text-slate-400 hover:text-slate-600'
                        "
                        @click="editLocale = loc"
                    >
                        {{ localeLabels[loc] }}
                    </button>
                </span>
                <input v-model="name" type="text" :class="fieldClasses" />
                <span v-if="form.errors.name" class="text-xs text-red-600">
                    {{ form.errors.name }}
                </span>
            </label>

            <label class="grid gap-1.5">
                <span
                    class="text-xs font-medium text-slate-600 dark:text-slate-400"
                >
                    Slug
                </span>
                <input
                    v-model="form.slug"
                    type="text"
                    placeholder="gerado pelo nome"
                    :class="fieldClasses"
                />
            </label>

            <div class="flex items-end gap-2">
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
                    {{ editing ? 'Salvar' : 'Adicionar' }}
                </button>
                <button
                    v-if="editing"
                    type="button"
                    class="inline-flex h-10 items-center rounded-lg border border-slate-200 px-3 text-sm text-slate-600 dark:border-slate-800 dark:text-slate-300"
                    @click="reset"
                >
                    <X class="size-4" aria-hidden="true" />
                </button>
            </div>
        </form>

        <ul class="grid gap-2">
            <li
                v-for="item in props.categories"
                :key="item.id"
                class="flex items-center justify-between gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3 dark:border-slate-800 dark:bg-slate-900"
            >
                <div class="min-w-0">
                    <p
                        class="truncate text-sm font-medium text-slate-900 dark:text-slate-100"
                    >
                        {{ item.name }}
                    </p>
                    <p class="truncate text-xs text-slate-400">
                        /blog/categoria/{{ item.slug }}
                    </p>
                </div>
                <div class="flex shrink-0 items-center gap-1">
                    <button
                        type="button"
                        class="rounded-md p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-slate-800"
                        aria-label="Editar categoria"
                        @click="edit(item)"
                    >
                        <Pencil class="size-3.5" aria-hidden="true" />
                    </button>
                    <button
                        type="button"
                        class="rounded-md p-1 text-slate-400 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-500/10"
                        aria-label="Excluir categoria"
                        @click="remove(item)"
                    >
                        <Trash2 class="size-3.5" aria-hidden="true" />
                    </button>
                </div>
            </li>
        </ul>

        <p
            v-if="props.categories.length === 0"
            class="rounded-xl border border-dashed border-slate-300 px-6 py-12 text-center text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400"
        >
            Nenhuma categoria criada.
        </p>
    </div>
</template>
