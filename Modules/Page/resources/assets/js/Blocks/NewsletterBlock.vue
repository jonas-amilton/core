<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { ArrowRight, Check } from '@lucide/vue';
import type { Block } from '../types';
import { store as newsletterStore } from '@/routes/newsletter';
import { useI18n } from '@/i18n';

const { t } = useI18n();

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
    <section
        class="border-t border-stone-200 px-4 py-16 sm:px-6 dark:border-stone-800"
    >
        <div class="mx-auto max-w-2xl text-center">
            <h2
                v-if="block.title"
                class="font-display text-3xl tracking-tight text-stone-950 sm:text-4xl dark:text-stone-50"
            >
                {{ block.title }}
            </h2>
            <p
                v-if="block.description"
                class="mx-auto mt-3 max-w-xl text-stone-600 dark:text-stone-400"
            >
                {{ block.description }}
            </p>

            <p
                v-if="form.recentlySuccessful"
                class="mt-6 inline-flex items-center gap-1.5 text-sm text-emerald-700 dark:text-emerald-400"
            >
                <Check class="size-4" aria-hidden="true" />
                {{ t('newsletter.success') }}
            </p>

            <form
                class="mx-auto mt-8 flex max-w-md items-end gap-3 border-b border-stone-400 pb-1 focus-within:border-orange-700 dark:border-stone-600 dark:focus-within:border-orange-400"
                @submit.prevent="submit"
            >
                <input
                    v-model="form.email"
                    type="email"
                    :placeholder="t('newsletter.placeholder')"
                    :aria-label="t('newsletter.aria')"
                    class="h-10 w-full bg-transparent px-1 text-sm outline-none placeholder:text-stone-500 dark:placeholder:text-stone-400"
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
                    class="inline-flex h-10 shrink-0 items-center gap-1.5 text-sm font-medium text-stone-900 transition-colors hover:text-orange-800 disabled:opacity-50 dark:text-stone-100 dark:hover:text-orange-400"
                    :disabled="form.processing"
                >
                    {{ t('newsletter.submit') }}
                    <ArrowRight class="size-4" aria-hidden="true" />
                </button>
            </form>
            <span
                v-if="form.errors.email"
                class="mt-2 block text-xs text-red-600"
            >
                {{ form.errors.email }}
            </span>
        </div>
    </section>
</template>
