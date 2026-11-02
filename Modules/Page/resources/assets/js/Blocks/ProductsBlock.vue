<script setup lang="ts">
import { useI18n } from '@/i18n';
import type { Block } from '../types';

const { t } = useI18n();

defineProps<{
    block: Block;
}>();
</script>

<template>
    <section class="px-4 py-14 sm:px-6 sm:py-20">
        <div class="w-full">
            <div class="flex flex-wrap items-baseline justify-between gap-3">
                <h2
                    v-if="block.title"
                    class="font-display text-3xl tracking-tight text-stone-950 sm:text-4xl dark:text-stone-50"
                >
                    {{ block.title }}
                </h2>
                <a
                    href="/produtos"
                    class="text-[11px] font-medium tracking-[0.14em] text-orange-800 uppercase underline-offset-4 hover:underline dark:text-orange-400"
                >
                    {{ t('products.viewCatalog') }}
                </a>
            </div>

            <ul
                class="mt-10 grid gap-x-8 gap-y-10 sm:grid-cols-2 lg:grid-cols-3"
            >
                <li v-for="(item, index) in block.items ?? []" :key="index">
                    <a :href="item.url ?? '/produtos'" class="group block">
                        <div
                            class="aspect-square border border-stone-200 bg-stone-50 dark:border-stone-800 dark:bg-stone-900"
                        >
                            <img
                                v-if="item.cover"
                                :src="item.cover"
                                alt=""
                                class="h-full w-full object-contain p-6 transition-transform duration-500 motion-safe:group-hover:scale-[1.02]"
                            />
                        </div>
                        <h3
                            class="mt-4 text-base font-medium text-stone-950 dark:text-stone-50"
                        >
                            {{ item.title }}
                        </h3>
                        <p
                            v-if="item.description"
                            class="mt-1 text-sm text-stone-600 dark:text-stone-400"
                        >
                            {{ item.description }}
                        </p>
                    </a>
                </li>
            </ul>
        </div>
    </section>
</template>
