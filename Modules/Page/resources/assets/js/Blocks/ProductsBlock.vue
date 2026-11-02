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
    <section class="px-4 py-10 sm:px-6">
        <div class="mx-auto max-w-6xl">
            <div class="flex flex-wrap items-end justify-between gap-3">
                <h2
                    v-if="block.title"
                    class="text-2xl font-semibold tracking-tight text-slate-950 dark:text-slate-50"
                >
                    {{ block.title }}
                </h2>
                <a
                    href="/produtos"
                    class="inline-flex h-9 items-center gap-1.5 rounded-full border border-green-900/25 px-4 text-sm font-medium text-green-950 transition-colors hover:bg-green-900 hover:text-white dark:border-slate-700 dark:text-slate-200"
                >
                    {{ t('products.viewCatalog') }}
                    <ArrowRight class="size-3.5" aria-hidden="true" />
                </a>
            </div>

            <ul class="mt-8 grid gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4">
                <li
                    v-for="(item, index) in block.items ?? []"
                    :key="index"
                    class="group flex flex-col"
                >
                    <a
                        :href="item.url ?? '/produtos'"
                        class="flex h-full flex-col"
                    >
                        <div
                            class="aspect-square overflow-hidden rounded-xl bg-slate-100 dark:bg-slate-800"
                        >
                            <img
                                v-if="item.cover"
                                :src="item.cover"
                                alt=""
                                class="h-full w-full object-contain p-6 mix-blend-multiply transition-transform duration-300 motion-safe:group-hover:scale-[1.03] dark:mix-blend-normal"
                            />
                        </div>

                        <div class="mt-4 flex items-start justify-between gap-3">
                            <h3
                                class="text-[15px] leading-snug font-semibold text-slate-950 group-hover:text-green-800 dark:text-slate-50"
                            >
                                {{ item.title }}
                            </h3>
                            <p
                                class="shrink-0 text-[15px] font-semibold text-slate-950 tabular-nums dark:text-slate-50"
                            >
                                {{
                                    formatPrice(item.price) ??
                                    t('catalog.priceOnRequest')
                                }}
                            </p>
                        </div>

                        <p
                            v-if="item.description"
                            class="mt-1 line-clamp-1 text-xs text-slate-500 dark:text-slate-400"
                        >
                            {{ item.description }}
                        </p>

                        <span
                            class="mt-4 inline-flex h-10 items-center justify-center rounded-full border border-green-900/30 text-sm font-medium text-green-950 transition-colors group-hover:border-green-900 group-hover:bg-green-900 group-hover:text-white dark:border-slate-700 dark:text-slate-200"
                        >
                            {{ t('catalog.view') }}
                        </span>
                    </a>
                </li>
            </ul>
        </div>
    </section>
</template>
