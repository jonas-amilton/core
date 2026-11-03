<script setup lang="ts">
import { ArrowUpRight } from '@lucide/vue';
import { useI18n } from '@/i18n';
import type { Block } from '@modules/Page/resources/assets/js/types';

const { t } = useI18n();

defineProps<{
    block: Block;
}>();
</script>

<template>
    <section class="bg-white px-4 py-12 sm:px-6 sm:py-16 dark:bg-slate-950">
        <div class="mx-auto max-w-5xl">
            <h2
                v-if="block.title"
                class="text-2xl font-semibold tracking-tight text-slate-950 sm:text-3xl dark:text-slate-50"
            >
                {{ block.title }}
            </h2>
            <p
                v-if="block.subtitle"
                class="mt-2 max-w-2xl text-slate-700 dark:text-slate-400"
            >
                {{ block.subtitle }}
            </p>

            <ul class="mt-8 grid gap-4 sm:grid-cols-2">
                <li v-for="item in block.items ?? []" :key="item.title">
                    <a
                        :href="item.url ?? '#'"
                        class="group flex h-full items-start gap-4 rounded-lg border border-slate-200 bg-white p-5 transition-colors hover:border-green-300 dark:border-slate-800 dark:bg-slate-900 dark:hover:border-brand/40"
                    >
                        <img
                            v-if="item.icon"
                            :src="item.icon"
                            alt=""
                            class="size-8 shrink-0 object-contain"
                        />
                        <span
                            v-else
                            class="mt-1 size-2 shrink-0 rounded-sm bg-brand"
                            aria-hidden="true"
                        />
                        <span class="min-w-0 flex-1">
                            <span
                                class="flex items-center justify-between gap-3 text-base font-semibold text-slate-950 dark:text-slate-50"
                            >
                                {{ item.title }}
                                <ArrowUpRight
                                    class="size-4 shrink-0 text-slate-400 transition-colors group-hover:text-brand dark:group-hover:text-green-400"
                                    aria-hidden="true"
                                />
                            </span>
                            <span
                                class="mt-1 block text-sm leading-relaxed text-slate-700 dark:text-slate-400"
                            >
                                {{ item.description }}
                            </span>
                        </span>
                    </a>
                </li>
            </ul>

            <p
                v-if="(block.items ?? []).length === 0"
                class="mt-4 text-sm text-slate-600 dark:text-slate-400"
            >
                {{ t('services.empty') }}
            </p>
        </div>
    </section>
</template>
