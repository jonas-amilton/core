<script setup lang="ts">
import { ArrowUpRight } from '@lucide/vue';
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
            <h2
                v-if="block.title"
                class="font-display text-3xl tracking-tight text-stone-950 sm:text-4xl dark:text-stone-50"
            >
                {{ block.title }}
            </h2>
            <p
                v-if="block.subtitle"
                class="mt-2 max-w-2xl text-stone-600 dark:text-stone-400"
            >
                {{ block.subtitle }}
            </p>

            <ul
                class="mt-8 grid border-t border-stone-200 sm:grid-cols-2 sm:gap-x-10 dark:border-stone-800"
            >
                <li
                    v-for="item in block.items ?? []"
                    :key="item.title"
                    class="border-b border-stone-200 dark:border-stone-800"
                >
                    <a
                        :href="item.url ?? '#'"
                        class="group flex items-center justify-between gap-4 py-5"
                    >
                        <span class="flex items-center gap-3">
                            <img
                                v-if="item.icon"
                                :src="item.icon"
                                alt=""
                                class="size-6 shrink-0 object-contain"
                            />
                            <span
                                class="text-base text-stone-900 dark:text-stone-100"
                            >
                                {{ item.title }}
                            </span>
                        </span>
                        <ArrowUpRight
                            class="size-4 shrink-0 text-stone-400 transition-colors group-hover:text-orange-700 dark:group-hover:text-orange-400"
                            aria-hidden="true"
                        />
                    </a>
                </li>
            </ul>

            <p
                v-if="(block.items ?? []).length === 0"
                class="mt-4 text-sm text-stone-500 dark:text-stone-400"
            >
                {{ t('services.empty') }}
            </p>
        </div>
    </section>
</template>
