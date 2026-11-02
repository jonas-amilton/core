<script setup lang="ts">
import type { Block } from '../types';

defineProps<{
    block: Block;
}>();
</script>

<template>
    <section class="px-4 py-12 sm:px-6">
        <div class="flex flex-wrap items-end justify-between gap-2">
            <h2
                v-if="block.title"
                class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-slate-100"
            >
                {{ block.title }}
            </h2>
            <a
                href="/blog"
                class="text-sm font-medium text-indigo-600 hover:underline dark:text-indigo-400"
            >
                Ver todos
            </a>
        </div>

        <ul class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <li v-for="(item, index) in block.items ?? []" :key="index">
                <a
                    :href="item.url ?? '/blog'"
                    class="flex h-full flex-col overflow-hidden rounded-xl border border-slate-200 transition-colors hover:border-indigo-300 dark:border-slate-800 dark:hover:border-indigo-500/40"
                >
                    <img
                        v-if="item.cover"
                        :src="item.cover"
                        alt=""
                        class="aspect-video w-full object-cover"
                    />
                    <div class="flex flex-1 flex-col p-4">
                        <p class="text-xs text-slate-400">
                            <template v-if="item.date">{{
                                item.date
                            }}</template>
                            <template v-if="item.date && item.category">
                                ·
                            </template>
                            <template v-if="item.category">
                                {{ item.category }}
                            </template>
                        </p>
                        <h3
                            class="mt-1 text-sm font-semibold text-slate-900 dark:text-slate-100"
                        >
                            {{ item.title }}
                        </h3>
                        <p
                            v-if="item.description"
                            class="mt-1 line-clamp-2 text-sm text-slate-600 dark:text-slate-400"
                        >
                            {{ item.description }}
                        </p>
                    </div>
                </a>
            </li>
        </ul>
    </section>
</template>
