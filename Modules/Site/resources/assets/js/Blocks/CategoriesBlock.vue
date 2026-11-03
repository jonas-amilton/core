<script setup lang="ts">
import { ArrowUpRight } from '@lucide/vue';
import { useI18n } from '@/i18n';
import type { Block } from '@modules/Page/resources/assets/js/types';

const { t } = useI18n();

defineProps<{
    block: Block;
}>();

const pastels = [
    'bg-[#fdf3c9]',
    'bg-[#fbe3e3]',
    'bg-[#d9f3ea]',
    'bg-[#e8e4fb]',
];
</script>

<template>
    <section class="px-4 py-6 sm:px-6">
        <div class="mx-auto max-w-6xl">
            <div class="flex flex-wrap items-end justify-between gap-3">
                <div>
                    <h2
                        v-if="block.title"
                        class="text-2xl font-semibold tracking-tight text-slate-950 dark:text-slate-50"
                    >
                        {{ block.title }}
                    </h2>
                    <p
                        v-if="block.subtitle"
                        class="mt-1 text-sm text-slate-700 dark:text-slate-400"
                    >
                        {{ block.subtitle }}
                    </p>
                </div>
            </div>

            <ul class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <li
                    v-for="(item, index) in block.items ?? []"
                    :key="item.title"
                    class="group"
                >
                    <a
                        :href="item.url ?? '/produtos'"
                        :class="[
                            'relative flex min-h-44 flex-col justify-between overflow-hidden rounded-xl p-5 transition-transform duration-300 motion-safe:hover:-translate-y-0.5',
                            pastels[index % pastels.length],
                        ]"
                    >
                        <span class="relative z-10">
                            <span
                                class="block max-w-[75%] text-xl font-bold tracking-tight text-brand-deep"
                            >
                                {{ item.title }}
                            </span>
                            <span
                                class="mt-1 inline-flex items-center gap-1 text-xs font-medium text-brand-deep/80"
                            >
                                {{
                                    t('catalog.itemsCount').replace(
                                        ':count',
                                        String(item.count ?? 0),
                                    )
                                }}
                            </span>
                        </span>

                        <img
                            v-if="item.cover"
                            :src="item.cover"
                            alt=""
                            class="absolute right-2 bottom-2 h-28 w-28 object-contain mix-blend-multiply transition-transform duration-300 motion-safe:group-hover:scale-105"
                        />

                        <ArrowUpRight
                            class="absolute top-4 right-4 size-5 text-brand-deep/50"
                            aria-hidden="true"
                        />
                    </a>
                </li>
            </ul>
        </div>
    </section>
</template>
