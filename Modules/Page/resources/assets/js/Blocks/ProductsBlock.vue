<script setup lang="ts">
import { ArrowRight } from '@lucide/vue';
import { formatPrice } from '@/lib/format';
import { useI18n } from '@/i18n';
import type { Block } from '../types';

const { t } = useI18n();

defineProps<{
    block: Block;
}>();
</script>

<template>
    <section class="px-4 py-12 sm:px-6 sm:py-16">
        <div class="mx-auto max-w-5xl">
            <div class="flex flex-wrap items-end justify-between gap-3">
                <h2
                    v-if="block.title"
                    class="text-2xl font-semibold tracking-tight text-slate-950 sm:text-3xl dark:text-slate-50"
                >
                    {{ block.title }}
                </h2>
                <a
                    href="/produtos"
                    class="inline-flex h-9 items-center gap-1.5 rounded-md border border-slate-300 px-3.5 text-xs font-medium text-slate-700 transition-colors hover:border-blue-300 hover:text-blue-800 dark:border-slate-700 dark:text-slate-300 dark:hover:border-blue-500/40 dark:hover:text-blue-400"
                >
                    {{ t('products.viewCatalog') }}
                    <ArrowRight class="size-3.5" aria-hidden="true" />
                </a>
            </div>

            <ul class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                <li v-for="(item, index) in block.items ?? []" :key="index">
                    <a
                        :href="item.url ?? '/produtos'"
                        class="group flex h-full flex-col overflow-hidden rounded-lg border border-slate-200 bg-white shadow-xs transition-colors hover:border-blue-300 dark:border-slate-800 dark:bg-slate-900 dark:hover:border-blue-500/40"
                    >
                        <div
                            class="aspect-square border-b border-slate-100 bg-slate-50 dark:border-slate-800 dark:bg-slate-800/60"
                        >
                            <img
                                v-if="item.cover"
                                :src="item.cover"
                                alt=""
                                class="h-full w-full object-contain p-6 transition-transform duration-500 motion-safe:group-hover:scale-[1.02]"
                            />
                        </div>
                        <div class="flex flex-1 flex-col p-5">
                            <h3
                                class="text-base font-semibold text-slate-950 group-hover:text-blue-800 dark:text-slate-50 dark:group-hover:text-blue-400"
                            >
                                {{ item.title }}
                            </h3>
                            <p
                                v-if="item.description"
                                class="mt-1 line-clamp-2 text-sm text-slate-600 dark:text-slate-400"
                            >
                                {{ item.description }}
                            </p>
                            <p
                                class="mt-3 flex-1 text-lg font-semibold tracking-tight text-slate-950 tabular-nums dark:text-slate-50"
                            >
                                {{
                                    formatPrice(item.price) ??
                                    t('catalog.priceOnRequest')
                                }}
                            </p>
                            <span
                                class="mt-4 inline-flex items-center gap-1.5 text-xs font-medium text-blue-800 dark:text-blue-400"
                            >
                                {{ t('catalog.view') }}
                                <ArrowRight class="size-3.5" aria-hidden="true" />
                            </span>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </section>
</template>
