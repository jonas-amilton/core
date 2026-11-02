<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import { Check, Pencil, Plus, Trash2, X } from '@lucide/vue';
import { computed, ref } from 'vue';
import { destroy, index as faqIndex, store, update } from '@/routes/faq';
import { fieldClasses } from '@/lib/formStyles';

type Faq = {
    id: number;
    question: string;
    answer: string;
    sort_order: number;
    translations: Record<'en' | 'es', { question: string | null; answer: string | null }>;
};

const props = defineProps<{
    faqs: Faq[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Perguntas frequentes',
                href: faqIndex(),
            },
        ],
    },
});

const editing = ref<Faq | null>(null);

type EditLocale = 'pt_BR' | 'en' | 'es';

const locales: EditLocale[] = ['pt_BR', 'en', 'es'];
const localeLabels: Record<EditLocale, string> = {
    pt_BR: 'PT',
    en: 'EN',
    es: 'ES',
};
const editLocale = ref<EditLocale>('pt_BR');

const form = useForm({
    question: '',
    answer: '',
    sort_order: 0,
    translations: {
        en: { question: '', answer: '' },
        es: { question: '', answer: '' },
    },
});

function translatedField(field: 'question' | 'answer') {
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

const question = translatedField('question');
const answer = translatedField('answer');

function reset(): void {
    editing.value = null;
    form.reset();
    form.clearErrors();
}

function edit(item: Faq): void {
    editing.value = item;
    editLocale.value = 'pt_BR';
    form.question = item.question;
    form.answer = item.answer;
    form.sort_order = item.sort_order;
    form.translations.en.question = item.translations?.en?.question ?? '';
    form.translations.en.answer = item.translations?.en?.answer ?? '';
    form.translations.es.question = item.translations?.es?.question ?? '';
    form.translations.es.answer = item.translations?.es?.answer ?? '';
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

function remove(item: Faq): void {
    if (!window.confirm(`Excluir "${item.question}"?`)) {
        return;
    }

    router.delete(destroy.url(item.id));
}
</script>

<template>
    <Head title="Perguntas frequentes" />

    <div class="flex flex-1 flex-col gap-6 p-4 sm:p-6">
        <header>
            <h1
                class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-slate-100"
            >
                Perguntas frequentes
            </h1>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
                Aparecem no bloco "FAQ" das páginas.
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
                    {{ editing ? 'Editar pergunta' : 'Nova pergunta' }}
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

            <div class="flex items-center gap-1">
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
                <span
                    class="text-xs font-medium text-slate-600 dark:text-slate-400"
                >
                    Pergunta
                </span>
                <input
                    v-model="question"
                    type="text"
                    :class="fieldClasses"
                />
                <span v-if="form.errors.question" class="text-xs text-red-600">
                    {{ form.errors.question }}
                </span>
            </label>

            <label class="grid gap-1.5">
                <span
                    class="text-xs font-medium text-slate-600 dark:text-slate-400"
                >
                    Resposta
                </span>
                <textarea
                    v-model="answer"
                    :rows="4"
                    :class="[fieldClasses, 'h-auto py-2 leading-relaxed']"
                />
                <span v-if="form.errors.answer" class="text-xs text-red-600">
                    {{ form.errors.answer }}
                </span>
            </label>

            <label class="grid max-w-40 gap-1.5">
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

        <ul class="grid gap-2">
            <li
                v-for="item in props.faqs"
                :key="item.id"
                class="rounded-xl border border-slate-200 bg-white p-4 dark:border-slate-800 dark:bg-slate-900"
            >
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p
                            class="text-sm font-medium text-slate-900 dark:text-slate-100"
                        >
                            {{ item.question }}
                        </p>
                        <p
                            class="mt-1 line-clamp-3 text-sm text-slate-600 dark:text-slate-400"
                        >
                            {{ item.answer }}
                        </p>
                    </div>
                    <div class="flex shrink-0 items-start gap-1">
                        <button
                            type="button"
                            class="rounded-md p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-slate-800"
                            aria-label="Editar pergunta"
                            @click="edit(item)"
                        >
                            <Pencil class="size-3.5" aria-hidden="true" />
                        </button>
                        <button
                            type="button"
                            class="rounded-md p-1 text-slate-400 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-500/10"
                            aria-label="Excluir pergunta"
                            @click="remove(item)"
                        >
                            <Trash2 class="size-3.5" aria-hidden="true" />
                        </button>
                    </div>
                </div>
            </li>
        </ul>

        <p
            v-if="props.faqs.length === 0"
            class="rounded-xl border border-dashed border-slate-300 px-6 py-12 text-center text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400"
        >
            Nenhuma pergunta cadastrada.
        </p>
    </div>
</template>
