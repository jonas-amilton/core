<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Check } from '@lucide/vue';
import type { Block } from '../types';
import { store as newsletterStore } from '@/routes/newsletter';
import { fieldClasses } from '@/lib/formStyles';

defineProps<{
    block: Block;
}>();

const form = useForm({
    email: '',
    website: '',
});

function submit(): void {
    form.post(newsletterStore.url(), {
        preserveScroll: true,
        onSuccess: () => form.reset('email'),
    });
}
</script>

<template>
    <section class="px-4 py-12 sm:px-6">
        <div
            class="mx-auto flex max-w-3xl flex-col items-center gap-4 rounded-2xl border border-slate-200 bg-slate-50 p-6 text-center dark:border-slate-800 dark:bg-slate-900/60"
        >
            <h2
                v-if="block.title"
                class="text-xl font-semibold tracking-tight text-slate-900 dark:text-slate-100"
            >
                {{ block.title }}
            </h2>
            <p
                v-if="block.description"
                class="max-w-xl text-sm text-slate-600 dark:text-slate-400"
            >
                {{ block.description }}
            </p>

            <p
                v-if="form.recentlySuccessful"
                class="inline-flex items-center gap-1.5 text-sm text-emerald-600 dark:text-emerald-400"
            >
                <Check class="size-4" aria-hidden="true" />
                Inscrição confirmada. Obrigado!
            </p>

            <form class="flex w-full max-w-md gap-2" @submit.prevent="submit">
                <input
                    v-model="form.email"
                    type="email"
                    placeholder="seu@email.com"
                    aria-label="E-mail para newsletter"
                    :class="fieldClasses"
                />
                <input
                    v-model="form.website"
                    type="text"
                    name="website"
                    tabindex="-1"
                    autocomplete="off"
                    class="hidden"
                    aria-hidden="true"
                />
                <button
                    type="submit"
                    class="inline-flex h-10 shrink-0 items-center rounded-lg bg-indigo-600 px-4 text-sm font-medium text-white hover:bg-indigo-500 disabled:opacity-50"
                    :disabled="form.processing"
                >
                    Enviar
                </button>
            </form>
            <span v-if="form.errors.email" class="text-xs text-red-600">
                {{ form.errors.email }}
            </span>
        </div>
    </section>
</template>
