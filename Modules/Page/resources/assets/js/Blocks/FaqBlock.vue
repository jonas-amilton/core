<script setup lang="ts">
import type { Block } from '../types';

defineProps<{
    block: Block;
}>();
</script>

<template>
    <section class="bg-white px-4 py-12 sm:px-6 sm:py-16 dark:bg-slate-950">
        <div class="mx-auto max-w-3xl">
            <h2
                v-if="block.title"
                class="text-2xl font-semibold tracking-tight text-slate-950 sm:text-3xl dark:text-slate-50"
            >
                {{ block.title }}
            </h2>

            <div
                class="mt-6 divide-y divide-slate-200 overflow-hidden rounded-lg border border-slate-200 bg-white dark:divide-slate-800 dark:border-slate-800 dark:bg-slate-900"
            >
                <details
                    v-for="(item, index) in block.items ?? []"
                    :key="index"
                    class="group"
                >
                    <summary
                        class="flex cursor-pointer list-none items-center justify-between gap-4 px-5 py-4 text-[15px] font-medium text-slate-950 marker:content-none hover:bg-white dark:text-slate-50 dark:hover:bg-slate-800/60"
                    >
                        {{ item.title }}
                        <span
                            class="text-lg leading-none text-brand transition-transform group-open:rotate-45 dark:text-green-400"
                            aria-hidden="true"
                        >
                            +
                        </span>
                    </summary>
                    <p
                        class="px-5 pb-5 text-sm leading-relaxed whitespace-pre-line text-slate-600 dark:text-slate-400"
                    >
                        {{ item.description }}
                    </p>
                </details>
            </div>
        </div>
    </section>
</template>
