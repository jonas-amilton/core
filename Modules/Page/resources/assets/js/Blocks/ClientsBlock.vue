<script setup lang="ts">
import type { Block } from '../types';

defineProps<{
    block: Block;
}>();
</script>

<template>
    <section class="px-4 py-12 sm:px-6 sm:py-16">
        <div class="mx-auto max-w-5xl">
            <h2
                v-if="block.title"
                class="text-center text-2xl font-semibold tracking-tight text-slate-950 sm:text-3xl dark:text-slate-50"
            >
                {{ block.title }}
            </h2>

            <ul
                class="mt-8 grid grid-cols-2 gap-3 sm:grid-cols-3"
            >
                <li
                    v-for="(item, index) in block.items ?? []"
                    :key="index"
                    class="flex h-20 items-center justify-center rounded-lg border border-slate-200 bg-white p-4 dark:border-slate-800 dark:bg-slate-900"
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
                            class="max-h-9 max-w-full object-contain opacity-70 transition-opacity hover:opacity-100"
                        />
                        <span
                            v-else
                            class="text-center text-sm font-medium text-slate-500 dark:text-slate-400"
                        >
                            {{ item.title }}
                        </span>
                    </a>
                    <img
                        v-else-if="item.logo"
                        :src="item.logo"
                        :alt="item.title"
                        class="max-h-9 max-w-full object-contain opacity-70"
                    />
                    <span
                        v-else
                        class="text-center text-sm font-medium text-slate-500 dark:text-slate-400"
                    >
                        {{ item.title }}
                    </span>
                </li>
            </ul>
        </div>
    </section>
</template>
