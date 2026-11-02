<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { Check, Save } from '@lucide/vue';
import {
    index as settingsIndex,
    update as settingsUpdate,
} from '@/routes/settings';
import { fieldClasses } from '@/lib/formStyles';

type Field = {
    key: string;
    label: string;
    type: 'text' | 'textarea';
};

const props = defineProps<{
    values: Record<string, string>;
    fields: Field[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Configurações',
                href: settingsIndex(),
            },
        ],
    },
});

const form = useForm<Record<string, string>>({ ...props.values });

function save(): void {
    form.put(settingsUpdate.url(), { preserveScroll: true });
}
</script>

<template>
    <Head title="Configurações" />

    <div class="flex flex-1 flex-col gap-6 p-4 sm:p-6">
        <header>
            <h1
                class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-slate-100"
            >
                Configurações
            </h1>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
                Dados de contato, redes sociais e textos do site público.
            </p>
        </header>

        <form class="grid gap-4" @submit.prevent="save">
            <div class="grid gap-4 sm:grid-cols-2">
                <label
                    v-for="field in fields"
                    :key="field.key"
                    class="grid gap-1.5"
                    :class="{ 'sm:col-span-2': field.type === 'textarea' }"
                >
                    <span
                        class="text-xs font-medium text-slate-600 dark:text-slate-400"
                    >
                        {{ field.label }}
                    </span>
                    <textarea
                        v-if="field.type === 'textarea'"
                        v-model="form[field.key]"
                        :rows="3"
                        :class="[fieldClasses, 'h-auto py-2 leading-relaxed']"
                    />
                    <input
                        v-else
                        v-model="form[field.key]"
                        type="text"
                        :class="fieldClasses"
                    />
                    <span
                        v-if="form.errors[field.key]"
                        class="text-xs text-red-600 dark:text-red-400"
                    >
                        {{ form.errors[field.key] }}
                    </span>
                </label>
            </div>

            <div class="flex items-center gap-3">
                <button
                    type="submit"
                    class="inline-flex h-10 items-center gap-2 rounded-lg bg-indigo-600 px-4 text-sm font-medium text-white transition-colors hover:bg-indigo-500 disabled:opacity-50"
                    :disabled="form.processing"
                >
                    <Save class="size-4" aria-hidden="true" />
                    Salvar
                </button>
                <span
                    v-if="form.recentlySuccessful"
                    class="inline-flex items-center gap-1 text-sm text-emerald-600 dark:text-emerald-400"
                >
                    <Check class="size-4" aria-hidden="true" />
                    Salvo
                </span>
            </div>
        </form>
    </div>
</template>
