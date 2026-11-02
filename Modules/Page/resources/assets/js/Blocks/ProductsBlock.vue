<script setup lang="ts">
import { useI18n } from '@/i18n';
import type { Block } from '../types';

const { t } = useI18n();

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
                href="/produtos"
                class="text-sm font-medium text-indigo-600 hover:underline dark:text-indigo-400"
            >
                {{ t('products.viewCatalog') }}
            </a>
        </div>

        <ul class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <li v-for="(item, index) in block.items ?? []" :key="index">
                <a
                    :href="item.url ?? '/produtos'"
                    class="flex h-full flex-col overflow-hidden rounded-xl border border-slate-200 transition-colors hover:border-indigo-300 dark:border-slate-800 dark:hover:border-indigo-500/40"
                >
                    <img
                        v-if="item.cover"
                        :src="item.cover"
                        alt=""
                        class="aspect-square w-full object-contain p-4"
                    />
                    <div class="flex flex-1 flex-col border-t border-slate-100 p-4 dark:border-slate-800">
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-100">
                            {{ item.title }}
                        </h3>
                        <p v-if="item.description" class="mt-1 text-xs text-slate-500">
                            {{ item.description }}
                        </p>
                    </div>
                </a>
            </li>
        </ul>
    </section>
</template>
