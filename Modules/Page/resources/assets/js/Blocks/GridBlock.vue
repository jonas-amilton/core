<script setup lang="ts">
import type { Block } from '../types';

defineProps<{ block: Block }>();
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

            <ul
                class="mt-10 grid border-t border-l border-stone-200 sm:grid-cols-2 lg:grid-cols-3 dark:border-stone-800"
            >
                <li
                    v-for="(item, index) in block.items ?? []"
                    :key="index"
                    class="border-r border-b border-stone-200 p-6 dark:border-stone-800"
                >
                    <span
                        class="text-[11px] font-medium tracking-[0.16em] text-orange-800 tabular-nums dark:text-orange-400"
                    >
                        {{ String(index + 1).padStart(2, '0') }}
                    </span>
                    <p
                        class="mt-3 text-base font-medium text-stone-950 dark:text-stone-50"
                    >
                        {{ item.title }}
                    </p>
                    <p
                        v-if="item.description"
                        class="mt-2 text-sm leading-relaxed text-stone-600 dark:text-stone-400"
                    >
                        {{ item.description }}
                    </p>
                </li>
            </ul>
        </div>
    </section>
</template>
