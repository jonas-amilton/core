<script setup lang="ts">
import { ArrowRight } from '@lucide/vue';
import type { Block } from '../types';

defineProps<{ block: Block }>();
</script>

<template>
    <section
        class="border-b border-stone-200 bg-stone-900 px-4 py-16 text-stone-50 sm:px-6 sm:py-24 dark:border-stone-800 dark:bg-stone-100 dark:text-stone-900"
    >
        <div class="w-full">
            <span class="block h-px w-12 bg-orange-500" aria-hidden="true" />

            <h1
                class="mt-8 max-w-4xl font-display text-4xl leading-[1.02] tracking-tight text-balance sm:text-6xl lg:text-7xl"
            >
                {{ block.title }}
            </h1>

            <p
                v-if="block.subtitle"
                class="mt-6 max-w-xl text-base leading-relaxed text-stone-300 sm:text-lg dark:text-stone-600"
            >
                {{ block.subtitle }}
            </p>

            <a
                v-if="block.button_label"
                :href="block.button_href || '#'"
                class="mt-10 inline-flex items-center gap-2 border-b border-orange-500 pb-1 text-sm font-medium tracking-wide text-stone-50 transition-colors hover:text-orange-300 dark:text-stone-900 dark:hover:text-orange-800"
            >
                {{ block.button_label }}
                <ArrowRight class="size-4" aria-hidden="true" />
            </a>
        </div>
    </section>
</template>
