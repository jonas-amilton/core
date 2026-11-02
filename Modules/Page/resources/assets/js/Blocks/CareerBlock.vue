<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Check } from '@lucide/vue';
import type { Block } from '../types';
import { store as careerStore } from '@/routes/contact/career';
import { fieldClasses } from '@/lib/formStyles';
import { useI18n } from '@/i18n';

const { t } = useI18n();

defineProps<{
    block: Block;
}>();

const form = useForm({
    name: '',
    email: '',
    phone: '',
    message: '',
    website: '',
});

function submit(): void {
    form.post(careerStore.url(), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
}
</script>

<template>
    <section class="px-4 py-12 sm:px-6">
        <div class="mx-auto max-w-2xl">
            <h2
                v-if="block.title"
                class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-slate-100"
            >
                {{ block.title }}
            </h2>
            <p v-if="block.subtitle" class="mt-1 text-slate-600 dark:text-slate-400">
                {{ block.subtitle }}
            </p>

            <p
                v-if="form.recentlySuccessful"
                class="mt-4 inline-flex items-center gap-1.5 rounded-lg bg-emerald-50 px-3 py-2 text-sm text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300"
            >
                <Check class="size-4" aria-hidden="true" />
                {{ t('career.success') }}
            </p>

            <form class="mt-6 grid gap-3 sm:grid-cols-2" @submit.prevent="submit">
                <label class="grid gap-1.5">
                    <span class="text-xs font-medium text-slate-600 dark:text-slate-400">
                        {{ t('contact.name') }}
                    </span>
                    <input v-model="form.name" type="text" :class="fieldClasses" />
                    <span v-if="form.errors.name" class="text-xs text-red-600">
                        {{ form.errors.name }}
                    </span>
                </label>

                <label class="grid gap-1.5">
                    <span class="text-xs font-medium text-slate-600 dark:text-slate-400">
                        {{ t('contact.email') }}
                    </span>
                    <input v-model="form.email" type="email" :class="fieldClasses" />
                    <span v-if="form.errors.email" class="text-xs text-red-600">
                        {{ form.errors.email }}
                    </span>
                </label>

                <label class="grid gap-1.5 sm:col-span-2">
                    <span class="text-xs font-medium text-slate-600 dark:text-slate-400">
                        {{ t('contact.phone') }}
                    </span>
                    <input v-model="form.phone" type="tel" :class="fieldClasses" />
                </label>

                <label class="grid gap-1.5 sm:col-span-2">
                    <span class="text-xs font-medium text-slate-600 dark:text-slate-400">
                        {{ t('career.presentation') }}
                    </span>
                    <textarea
                        v-model="form.message"
                        :rows="5"
                        :class="[fieldClasses, 'h-auto py-2 leading-relaxed']"
                    />
                    <span v-if="form.errors.message" class="text-xs text-red-600">
                        {{ form.errors.message }}
                    </span>
                </label>

                <input
                    v-model="form.website"
                    type="text"
                    name="website"
                    tabindex="-1"
                    autocomplete="off"
                    class="hidden"
                    aria-hidden="true"
                />

                <div class="sm:col-span-2">
                    <button
                        type="submit"
                        class="inline-flex h-10 items-center rounded-lg bg-indigo-600 px-5 text-sm font-medium text-white hover:bg-indigo-500 disabled:opacity-50"
                        :disabled="form.processing"
                    >
                        {{ t('career.submit') }}
                    </button>
                </div>
            </form>
        </div>
    </section>
</template>
