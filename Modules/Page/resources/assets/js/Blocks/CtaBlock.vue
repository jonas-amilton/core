<script setup lang="ts">
import { ArrowRight } from '@lucide/vue';
import type { Block } from '../types';

defineProps<{ block: Block }>();
</script>

<template>
    <section
        class="border-t border-stone-200 bg-stone-100/70 px-4 py-14 sm:px-6 sm:py-16 dark:border-stone-800 dark:bg-stone-900/40"
    >
        <div
            class="mx-auto flex max-w-5xl flex-col gap-6 sm:flex-row sm:items-end sm:justify-between"
        >
            <div class="max-w-2xl">
                <span
                    class="block h-px w-10 bg-orange-700"
                    aria-hidden="true"
                />
                <p
                    class="mt-5 font-display text-3xl leading-tight tracking-tight text-stone-950 sm:text-4xl dark:text-stone-50"
                >
                    {{ block.title }}
                </p>
                <p
                    v-if="block.description"
                    class="mt-3 text-stone-600 dark:text-stone-400"
                >
                    {{ block.description }}
                </p>
            </div>

            <a
                v-if="block.button_label"
                :href="block.button_href || '#'"
                class="inline-flex h-11 shrink-0 items-center gap-2 bg-stone-900 px-5 text-sm font-medium text-stone-50 transition-colors hover:bg-stone-800 dark:bg-stone-100 dark:text-stone-900 dark:hover:bg-stone-200"
            >
                {{ block.button_label }}
                <ArrowRight class="size-4" aria-hidden="true" />
            </a>
        </div>
    </section>
</template>
