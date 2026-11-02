<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft, Upload } from '@lucide/vue';
import { ref } from 'vue';
import { fieldClasses } from '@/lib/formStyles';
import { index as mediaIndex, store } from '@/routes/media';

defineProps<{
    maxKilobytes: number;
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

const form = useForm<{
    file: File | null;
    alt: string;
    caption: string;
}>({
    file: null,
    alt: '',
    caption: '',
});

const preview = ref<string | null>(null);

function onFile(event: Event): void {
    const input = event.target as HTMLInputElement;
    form.file = input.files?.[0] ?? null;

    if (preview.value) {
        URL.revokeObjectURL(preview.value);
    }

    preview.value = form.file ? URL.createObjectURL(form.file) : null;
}

function submit(): void {
    form.post(store().url, {
        forceFormData: true,
        preserveScroll: true,
    });
}
</script>

<template>
    <Head title="Enviar mídia" />

    <div class="flex flex-1 flex-col gap-6 p-4 sm:p-6">
        <header>
            <Link
                :href="mediaIndex()"
                class="inline-flex items-center gap-1.5 text-sm text-stone-600 hover:text-stone-900 dark:text-stone-400 dark:hover:text-stone-100"
            >
                <ArrowLeft class="size-4" aria-hidden="true" />
                Mídia
            </Link>
            <h1
                class="mt-2 text-2xl font-semibold tracking-tight text-stone-900 dark:text-stone-100"
            >
                Enviar arquivo
            </h1>
            <p class="mt-1 text-sm text-stone-600 dark:text-stone-400">
                JPG, PNG, WebP, GIF ou PDF — até
                {{ Math.round(maxKilobytes / 1024) }} MB.
            </p>
        </header>

        <form class="max-w-xl space-y-5" @submit.prevent="submit">
            <label
                class="flex cursor-pointer flex-col items-center justify-center gap-2 rounded-sm border border-dashed border-stone-300 bg-white px-6 py-10 text-center transition-colors hover:border-orange-400 dark:border-stone-700 dark:bg-stone-900"
            >
                <span
                    class="flex size-11 items-center justify-center rounded-sm bg-orange-50 text-orange-700 dark:bg-orange-600/10 dark:text-orange-400"
                >
                    <Upload class="size-5" aria-hidden="true" />
                </span>
                <span
                    class="text-sm font-medium text-stone-900 dark:text-stone-100"
                >
                    {{ form.file ? form.file.name : 'Selecionar arquivo' }}
                </span>
                <span class="text-xs text-stone-500 dark:text-stone-400">
                    Clique para escolher no computador
                </span>
                <input
                    type="file"
                    accept="image/jpeg,image/png,image/webp,image/gif,application/pdf"
                    class="sr-only"
                    @change="onFile"
                />
            </label>

            <p
                v-if="form.errors.file"
                class="text-sm text-red-600 dark:text-red-400"
            >
                {{ form.errors.file }}
            </p>

            <img
                v-if="preview"
                :src="preview"
                alt="Pré-visualização do arquivo"
                class="max-h-64 w-full rounded-sm border border-stone-200 object-contain dark:border-stone-800"
            />

            <div class="grid gap-2">
                <label
                    for="alt"
                    class="text-xs font-medium text-stone-600 dark:text-stone-400"
                >
                    Texto alternativo
                </label>
                <input
                    id="alt"
                    v-model="form.alt"
                    type="text"
                    placeholder="Descreva a imagem para leitores de tela"
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
                    class="text-xs font-medium text-stone-600 dark:text-stone-400"
                >
                    Legenda
                </label>
                <input
                    id="caption"
                    v-model="form.caption"
                    type="text"
                    placeholder="Opcional"
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
                class="inline-flex h-11 items-center justify-center gap-2 rounded-sm bg-stone-900 px-6 text-sm font-medium text-stone-50 dark:text-stone-900 dark:bg-stone-100 dark:hover:bg-stone-200 transition-colors hover:bg-stone-800 disabled:opacity-60"
                :disabled="form.processing || !form.file"
            >
                <Upload class="size-4" aria-hidden="true" />
                {{ form.processing ? 'Enviando...' : 'Enviar arquivo' }}
            </button>
        </form>
    </div>
</template>
