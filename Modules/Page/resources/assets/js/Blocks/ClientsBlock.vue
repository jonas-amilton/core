<script setup lang="ts">
import type { Block } from '../types';

defineProps<{
    block: Block;
}>();
</script>

<template>
    <section class="px-4 py-14 sm:px-6 sm:py-20">
        <div class="w-full">
            <h2
                v-if="block.title"
                class="font-display text-3xl tracking-tight text-stone-950 sm:text-4xl dark:text-stone-50"
            >
                {{ block.title }}
            </h2>

            <ul
                class="mt-10 grid grid-cols-2 border-t border-l border-stone-200 sm:grid-cols-3 lg:grid-cols-4 dark:border-stone-800"
            >
                <li
                    v-for="(item, index) in block.items ?? []"
                    :key="index"
                    class="flex h-24 items-center justify-center border-r border-b border-stone-200 p-6 dark:border-stone-800"
                >
                    <a
                        v-if="item.url"
                        :href="item.url"
                        target="_blank"
                        rel="noopener"
                        class="flex h-full w-full items-center justify-center"
                    >
                        <img
                            v-if="item.logo"
                            :src="item.logo"
                            :alt="item.title"
                            class="max-h-10 max-w-full object-contain opacity-60 grayscale transition-opacity hover:opacity-100"
                        />
                        <span
                            v-else
                            class="text-sm text-stone-600 dark:text-stone-400"
                        >
                            {{ item.title }}
                        </span>
                    </a>
                    <img
                        v-else-if="item.logo"
                        :src="item.logo"
                        :alt="item.title"
                        class="max-h-10 max-w-full object-contain opacity-60 grayscale"
                    />
                    <span
                        v-else
                        class="text-sm text-stone-600 dark:text-stone-400"
                    >
                        {{ item.title }}
                    </span>
                </li>
            </ul>
        </div>
    </section>
</template>
