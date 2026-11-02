<script setup lang="ts">
import type { Block } from '../types';

defineProps<{
    block: Block;
}>();
</script>

<template>
    <section class="px-4 py-12 sm:px-6">
        <h2
            v-if="block.title"
            class="text-center text-2xl font-semibold tracking-tight text-slate-900 dark:text-slate-100"
        >
            {{ block.title }}
        </h2>

        <ul
            class="mt-6 grid grid-cols-2 items-center gap-6 sm:grid-cols-3 lg:grid-cols-4"
        >
            <li
                v-for="(item, index) in block.items ?? []"
                :key="index"
                class="flex h-20 items-center justify-center rounded-lg border border-slate-200 p-3 dark:border-slate-800"
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
                        class="max-h-12 max-w-full object-contain"
                    />
                    <span
                        v-else
                        class="text-sm text-slate-600 dark:text-slate-400"
                    >
                        {{ item.title }}
                    </span>
                </a>
                <img
                    v-else-if="item.logo"
                    :src="item.logo"
                    :alt="item.title"
                    class="max-h-12 max-w-full object-contain"
                />
                <span v-else class="text-sm text-slate-600 dark:text-slate-400">
                    {{ item.title }}
                </span>
            </li>
        </ul>
    </section>
</template>
