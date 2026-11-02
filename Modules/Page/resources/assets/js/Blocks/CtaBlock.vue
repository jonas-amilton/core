<script setup lang="ts">
import { ArrowRight } from '@lucide/vue';
import type { Block } from '../types';

defineProps<{ block: Block }>();
</script>

<template>
    <section
        class="bg-slate-900 px-4 py-12 text-white sm:px-6 sm:py-14 dark:border-y dark:border-slate-800"
    >
        <div
            class="mx-auto flex max-w-5xl flex-col gap-6 sm:flex-row sm:items-center sm:justify-between"
        >
            <div class="max-w-2xl">
                <p
                    class="text-2xl font-semibold tracking-tight sm:text-3xl"
                >
                    {{ block.title }}
                </p>
                <p
                    v-if="block.description"
                    class="mt-2 text-sm leading-relaxed text-slate-300 sm:text-base"
                >
                    {{ block.description }}
                </p>
            </div>

            <a
                v-if="block.button_label"
                :href="block.button_href || '#'"
                class="inline-flex h-11 shrink-0 items-center justify-center gap-2 rounded-md bg-blue-700 px-5 text-sm font-medium text-white transition-colors hover:bg-blue-600"
            >
                {{ block.button_label }}
                <ArrowRight class="size-4" aria-hidden="true" />
            </a>
        </div>
    </section>
</template>
