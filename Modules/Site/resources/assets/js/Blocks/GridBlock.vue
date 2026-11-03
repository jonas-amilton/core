<script setup lang="ts">
import { Check } from '@lucide/vue';
import type { Block } from '@modules/Page/resources/assets/js/types';

defineProps<{ block: Block }>();
</script>

<template>
    <section class="px-4 py-12 sm:px-6 sm:py-16">
        <div class="mx-auto max-w-5xl">
            <h2
                v-if="block.title"
                class="text-2xl font-semibold tracking-tight text-slate-950 sm:text-3xl dark:text-slate-50"
            >
                {{ block.title }}
            </h2>

            <ul class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                <li
                    v-for="(item, index) in block.items ?? []"
                    :key="index"
                    class="rounded-lg border border-slate-200 bg-white p-6 shadow-xs dark:border-slate-800 dark:bg-slate-900"
                >
                    <span
                        class="flex size-9 items-center justify-center rounded-md bg-cream text-brand dark:bg-brand/10 dark:text-green-400"
                        aria-hidden="true"
                    >
                        <Check class="size-4" />
                    </span>
                    <p
                        class="mt-4 text-base font-semibold text-slate-950 dark:text-slate-50"
                    >
                        {{ item.title }}
                    </p>
                    <p
                        v-if="item.description"
                        class="mt-2 text-sm leading-relaxed text-slate-700 dark:text-slate-400"
                    >
                        {{ item.description }}
                    </p>
                </li>
            </ul>
        </div>
    </section>
</template>
