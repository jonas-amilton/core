<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { ArrowRight, Check } from '@lucide/vue';
import type { Block } from '../types';
import { store as newsletterStore } from '@/routes/newsletter';
import { useI18n } from '@/i18n';
import { siteFieldClasses } from '@/lib/formStyles';

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
    <section class="px-4 py-12 sm:px-6 sm:py-16">
        <div
            class="mx-auto max-w-3xl rounded-lg border border-slate-200 bg-white p-6 text-center shadow-xs sm:p-10 dark:border-slate-800 dark:bg-slate-900"
        >
            <h2
                v-if="block.title"
                class="text-2xl font-semibold tracking-tight text-slate-950 dark:text-slate-50"
            >
                {{ block.title }}
            </h2>
            <p
                v-if="block.description"
                class="mx-auto mt-2 max-w-xl text-sm leading-relaxed text-slate-600 dark:text-slate-400"
            >
                {{ block.description }}
            </p>

            <p
                v-if="form.recentlySuccessful"
                class="mt-5 inline-flex items-center gap-1.5 text-sm text-emerald-700 dark:text-emerald-400"
            >
                <Check class="size-4" aria-hidden="true" />
                {{ t('newsletter.success') }}
            </p>

            <form
                class="mx-auto mt-6 flex max-w-md flex-col gap-2 sm:flex-row"
                @submit.prevent="submit"
            >
                <input
                    v-model="form.email"
                    type="email"
                    :placeholder="t('newsletter.placeholder')"
                    :aria-label="t('newsletter.aria')"
                    :class="[siteFieldClasses, 'h-11 flex-1']"
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
                    class="inline-flex h-11 shrink-0 items-center justify-center gap-2 rounded-md bg-blue-800 px-5 text-sm font-medium text-white transition-colors hover:bg-blue-900 disabled:opacity-50"
                    :disabled="form.processing"
                >
                    {{ t('newsletter.submit') }}
                    <ArrowRight class="size-4" aria-hidden="true" />
                </button>
            </form>
            <span v-if="form.errors.email" class="mt-2 block text-xs text-red-600">
                {{ form.errors.email }}
            </span>
        </div>
    </section>
</template>
