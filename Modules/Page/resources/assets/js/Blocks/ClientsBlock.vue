<script setup lang="ts">
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
            <h2
                v-if="block.title"
                class="text-2xl font-semibold tracking-tight text-slate-950 dark:text-slate-50"
            >
                {{ block.title }}
            </h2>

            <ul class="mt-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                <li
                    v-for="(item, index) in block.items ?? []"
                    :key="index"
                    class="flex items-center gap-3 rounded-xl bg-white p-4 dark:bg-slate-900"
                >
                    <span
                        class="flex size-11 shrink-0 items-center justify-center rounded-full bg-white dark:bg-slate-800"
                    >
                        <a
                            v-if="item.url"
                            :href="item.url"
                            target="_blank"
                            rel="noopener"
                            class="flex size-full items-center justify-center"
                        >
                            <img
                                v-if="item.logo"
                                :src="item.logo"
                                :alt="item.title"
                                class="size-6 object-contain"
                            />
                            <span
                                v-else
                                class="text-xs font-semibold text-slate-600"
                            >
                                {{ item.title.charAt(0) }}
                            </span>
                        </a>
                        <template v-else>
                            <img
                                v-if="item.logo"
                                :src="item.logo"
                                :alt="item.title"
                                class="size-6 object-contain"
                            />
                            <span
                                v-else
                                class="text-xs font-semibold text-slate-600"
                            >
                                {{ item.title.charAt(0) }}
                            </span>
                        </template>
                    </span>

                    <span class="min-w-0">
                        <span
                            class="block truncate text-sm font-medium text-slate-900 dark:text-slate-100"
                        >
                            {{ item.title }}
                        </span>
                        <span class="block text-xs text-slate-600 dark:text-slate-400">
                            {{ t('clients.supply') }}
                        </span>
                    </span>
                </li>
            </ul>
        </div>
    </section>
</template>
