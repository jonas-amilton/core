<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import { Check, Pencil, Plus, Trash2, X } from '@lucide/vue';
import { computed, ref } from 'vue';
import MediaPicker from '@modules/Media/resources/assets/js/components/MediaPicker.vue';
import type { PickedMedia } from '@modules/Media/resources/assets/js/types';
import {
    destroy,
    index as testimonialIndex,
    store,
    update,
} from '@/routes/testimonial';
import { fieldClasses } from '@/lib/formStyles';

type Testimonial = {
    id: number;
    author: string;
    role: string | null;
    quote: string;
    avatar: string | null;
    sort_order: number;
    translations: Record<'en' | 'es', { role: string | null; quote: string | null }>;
};

const props = defineProps<{
    testimonials: Testimonial[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Depoimentos',
                href: testimonialIndex(),
            },
        ],
    },
});

const editing = ref<Testimonial | null>(null);
const pickerOpen = ref(false);

type EditLocale = 'pt_BR' | 'en' | 'es';

const locales: EditLocale[] = ['pt_BR', 'en', 'es'];
const localeLabels: Record<EditLocale, string> = {
    pt_BR: 'PT',
    en: 'EN',
    es: 'ES',
};
const editLocale = ref<EditLocale>('pt_BR');

const form = useForm({
    author: '',
    role: '',
    quote: '',
    avatar: '',
    sort_order: 0,
    translations: {
        en: { role: '', quote: '' },
        es: { role: '', quote: '' },
    },
});

function translatedField(field: 'role' | 'quote') {
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

const role = translatedField('role');
const quote = translatedField('quote');

function reset(): void {
    editing.value = null;
    form.reset();
    form.clearErrors();
}

function edit(item: Testimonial): void {
    editing.value = item;
    editLocale.value = 'pt_BR';
    form.author = item.author;
    form.role = item.role ?? '';
    form.quote = item.quote;
    form.avatar = item.avatar ?? '';
    form.sort_order = item.sort_order;
    form.translations.en.role = item.translations?.en?.role ?? '';
    form.translations.en.quote = item.translations?.en?.quote ?? '';
    form.translations.es.role = item.translations?.es?.role ?? '';
    form.translations.es.quote = item.translations?.es?.quote ?? '';
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

function remove(item: Testimonial): void {
    if (!window.confirm(`Excluir o depoimento de "${item.author}"?`)) {
        return;
    }

    router.delete(destroy.url(item.id));
}

function pickAvatar(media: PickedMedia): void {
    form.avatar = media.url;
}
</script>

<template>
    <Head title="Depoimentos" />

    <div class="flex flex-1 flex-col gap-6 p-4 sm:p-6">
        <header>
            <h1
                class="text-2xl font-semibold tracking-tight text-stone-900 dark:text-stone-100"
            >
                Depoimentos
            </h1>
            <p class="mt-1 text-sm text-stone-600 dark:text-stone-400">
                Aparecem no bloco "Depoimentos" das páginas.
            </p>
        </header>

        <form
            class="grid gap-3 rounded-sm border border-stone-200 bg-white p-4 dark:border-stone-800 dark:bg-stone-900"
            @submit.prevent="submit"
        >
            <div class="flex items-center justify-between">
                <h2
                    class="text-sm font-semibold text-stone-900 dark:text-stone-100"
                >
                    {{ editing ? 'Editar depoimento' : 'Novo depoimento' }}
                </h2>
                <button
                    v-if="editing"
                    type="button"
                    class="inline-flex items-center gap-1 text-xs text-stone-500 hover:text-stone-700 dark:text-stone-400"
                    @click="reset"
                >
                    <X class="size-3.5" aria-hidden="true" />
                    Cancelar edição
                </button>
            </div>

            <div class="flex items-center gap-1">
                <button
                    v-for="loc in locales"
                    :key="loc"
                    type="button"
                    class="rounded-sm px-2 py-0.5 text-[11px] font-medium transition-colors"
                    :class="
                        editLocale === loc
                            ? 'bg-orange-100 text-orange-800 dark:bg-orange-600/20 dark:text-orange-300'
                            : 'text-stone-500 hover:bg-stone-100 dark:text-stone-500 dark:hover:bg-stone-800'
                    "
                    @click="editLocale = loc"
                >
                    {{ localeLabels[loc] }}
                </button>
                <span
                    v-if="editLocale !== 'pt_BR'"
                    class="ml-1 text-[11px] text-stone-400"
                >
                    Tradução — vazio usa o português
                </span>
            </div>

            <div class="grid gap-3 sm:grid-cols-2">
                <label class="grid gap-1.5">
                    <span
                        class="text-xs font-medium text-stone-600 dark:text-stone-400"
                    >
                        Autor
                    </span>
                    <input
                        v-model="form.author"
                        type="text"
                        :class="fieldClasses"
                    />
                    <span
                        v-if="form.errors.author"
                        class="text-xs text-red-600"
                    >
                        {{ form.errors.author }}
                    </span>
                </label>

                <label class="grid gap-1.5">
                    <span
                        class="text-xs font-medium text-stone-600 dark:text-stone-400"
                    >
                        Cargo
                    </span>
                    <input
                        v-model="role"
                        type="text"
                        :class="fieldClasses"
                    />
                </label>

                <label class="grid gap-1.5 sm:col-span-2">
                    <span
                        class="text-xs font-medium text-stone-600 dark:text-stone-400"
                    >
                        Depoimento
                    </span>
                    <textarea
                        v-model="quote"
                        :rows="4"
                        :class="[fieldClasses, 'h-auto py-2 leading-relaxed']"
                    />
                    <span v-if="form.errors.quote" class="text-xs text-red-600">
                        {{ form.errors.quote }}
                    </span>
                </label>

                <div class="grid gap-1.5">
                    <span
                        class="text-xs font-medium text-stone-600 dark:text-stone-400"
                    >
                        Foto
                    </span>
                    <div class="flex items-center gap-2">
                        <img
                            v-if="form.avatar"
                            :src="form.avatar"
                            alt=""
                            class="size-10 rounded-full object-cover"
                        />
                        <button
                            type="button"
                            class="h-10 rounded-sm border border-stone-200 px-3 text-sm font-medium text-stone-700 hover:bg-stone-50 dark:border-stone-800 dark:text-stone-200"
                            @click="pickerOpen = true"
                        >
                            Escolher imagem
                        </button>
                    </div>
                </div>

                <label class="grid gap-1.5">
                    <span
                        class="text-xs font-medium text-stone-600 dark:text-stone-400"
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

            <div class="flex items-center gap-3">
                <button
                    type="submit"
                    class="inline-flex h-10 items-center gap-2 rounded-sm bg-stone-900 px-4 text-sm font-medium text-stone-50 dark:text-stone-900 dark:bg-stone-100 dark:hover:bg-stone-200 hover:bg-stone-800 disabled:opacity-50"
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

        <ul class="grid gap-3">
            <li
                v-for="item in props.testimonials"
                :key="item.id"
                class="flex gap-3 rounded-sm border border-stone-200 bg-white p-4 dark:border-stone-800 dark:bg-stone-900"
            >
                <img
                    v-if="item.avatar"
                    :src="item.avatar"
                    alt=""
                    class="size-12 shrink-0 rounded-full object-cover"
                />
                <div class="min-w-0 flex-1">
                    <p
                        class="text-sm font-medium text-stone-900 dark:text-stone-100"
                    >
                        {{ item.author }}
                        <span
                            v-if="item.role"
                            class="font-normal text-stone-500"
                        >
                            · {{ item.role }}
                        </span>
                    </p>
                    <p
                        class="mt-1 line-clamp-3 text-sm text-stone-600 dark:text-stone-400"
                    >
                        {{ item.quote }}
                    </p>
                </div>
                <div class="flex shrink-0 items-start gap-1">
                    <button
                        type="button"
                        class="rounded-sm p-1 text-stone-400 hover:bg-stone-100 hover:text-stone-700 dark:hover:bg-stone-800"
                        aria-label="Editar depoimento"
                        @click="edit(item)"
                    >
                        <Pencil class="size-3.5" aria-hidden="true" />
                    </button>
                    <button
                        type="button"
                        class="rounded-sm p-1 text-stone-400 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-500/10"
                        aria-label="Excluir depoimento"
                        @click="remove(item)"
                    >
                        <Trash2 class="size-3.5" aria-hidden="true" />
                    </button>
                </div>
            </li>
        </ul>

        <p
            v-if="props.testimonials.length === 0"
            class="rounded-sm border border-dashed border-stone-300 px-6 py-12 text-center text-sm text-stone-500 dark:border-stone-700 dark:text-stone-400"
        >
            Nenhum depoimento cadastrado.
        </p>

        <MediaPicker
            :open="pickerOpen"
            @close="pickerOpen = false"
            @pick="pickAvatar"
        />
    </div>
</template>
