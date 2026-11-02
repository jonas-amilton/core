<script setup lang="ts">
import { ArrowRight } from '@lucide/vue';
import { useI18n } from '@/i18n';
import type { Block } from '../types';

const { t } = useI18n();

defineProps<{
    block: Block;
}>();
</script>

<template>
    <section class="bg-white px-4 py-12 sm:px-6 sm:py-16 dark:bg-slate-950">
        <div class="mx-auto max-w-5xl">
            <div class="flex flex-wrap items-end justify-between gap-3">
                <h2
                    v-if="block.title"
                    class="text-2xl font-semibold tracking-tight text-slate-950 sm:text-3xl dark:text-slate-50"
                >
                    {{ block.title }}
                </h2>
                <a
                    href="/blog"
                    class="inline-flex h-9 items-center gap-1.5 rounded-md border border-slate-300 px-3.5 text-xs font-medium text-slate-700 transition-colors hover:border-green-300 hover:text-brand dark:border-slate-700 dark:text-slate-300 dark:hover:border-brand/40 dark:hover:text-green-400"
                >
                    {{ t('posts.viewAll') }}
                    <ArrowRight class="size-3.5" aria-hidden="true" />
                </a>
            </div>

            <ul class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                <li v-for="(item, index) in block.items ?? []" :key="index">
                    <a
                        :href="item.url ?? '/blog'"
                        class="group flex h-full flex-col overflow-hidden rounded-lg border border-slate-200 bg-white shadow-xs transition-colors hover:border-green-300 dark:border-slate-800 dark:bg-slate-900 dark:hover:border-brand/40"
                    >
                        <div
                            v-if="item.cover"
                            class="aspect-[4/3] overflow-hidden bg-[#f5f5f5] dark:bg-slate-800"
                        >
                            <img
                                :src="item.cover"
                                alt=""
                                class="h-full w-full object-cover transition-transform duration-500 motion-safe:group-hover:scale-[1.02]"
                            />
                        </div>
                        <div class="flex flex-1 flex-col p-5">
                            <p
                                class="text-[11px] font-medium tracking-[0.1em] text-slate-500 uppercase dark:text-slate-400"
                            >
                                <template v-if="item.date">{{ item.date }}</template>
                                <template v-if="item.date && item.category">
                                    ·
                                </template>
                                <template v-if="item.category">
                                    {{ item.category }}
                                </template>
                            </p>
                            <h3
                                class="mt-2 text-base font-semibold text-slate-950 group-hover:text-brand dark:text-slate-50 dark:group-hover:text-green-400"
                            >
                                {{ item.title }}
                            </h3>
                            <p
                                v-if="item.description"
                                class="mt-2 line-clamp-2 flex-1 text-sm leading-relaxed text-slate-600 dark:text-slate-400"
                            >
                                {{ item.description }}
                            </p>
                            <span
                                class="mt-4 inline-flex items-center gap-1.5 text-xs font-medium text-brand dark:text-green-400"
                            >
                                {{ t('blog.read') }}
                                <ArrowRight class="size-3.5" aria-hidden="true" />
                            </span>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </section>
</template>
