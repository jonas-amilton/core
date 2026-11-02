<script setup lang="ts">
import type { Block } from '../types';

defineProps<{
    block: Block;
}>();
</script>

<template>
    <section class="px-4 py-14 sm:px-6 sm:py-20">
        <div class="mx-auto max-w-3xl">
            <h2
                v-if="block.title"
                class="font-display text-3xl tracking-tight text-stone-950 sm:text-4xl dark:text-stone-50"
            >
                {{ block.title }}
            </h2>

            <div class="mt-8 border-t border-stone-200 dark:border-stone-800">
                <details
                    v-for="(item, index) in block.items ?? []"
                    :key="index"
                    class="group border-b border-stone-200 dark:border-stone-800"
                >
                    <summary
                        class="flex cursor-pointer list-none items-baseline justify-between gap-4 py-4 text-base font-medium text-stone-950 marker:content-none dark:text-stone-50"
                    >
                        {{ item.title }}
                        <span
                            class="text-lg leading-none text-orange-700 transition-transform group-open:rotate-45 dark:text-orange-400"
                            aria-hidden="true"
                        >
                            +
                        </span>
                    </summary>
                    <p
                        class="mt-1 mb-5 pr-8 text-sm leading-relaxed whitespace-pre-line text-stone-600 dark:text-stone-400"
                    >
                        {{ item.description }}
                    </p>
                </details>
            </div>
        </div>
    </section>
</template>
