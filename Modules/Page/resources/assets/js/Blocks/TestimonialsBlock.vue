<script setup lang="ts">
import { Quote } from '@lucide/vue';
import type { Block } from '../types';

defineProps<{
    block: Block;
}>();
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
                    class="flex flex-col rounded-lg border border-slate-200 bg-white p-6 shadow-xs dark:border-slate-800 dark:bg-slate-900"
                >
                    <Quote
                        class="size-5 text-green-800 dark:text-green-400"
                        aria-hidden="true"
                    />
                    <p
                        class="mt-3 flex-1 text-sm leading-relaxed text-slate-700 dark:text-slate-300"
                    >
                        {{ item.description }}
                    </p>
                    <div class="mt-5 flex items-center gap-3 border-t border-slate-100 pt-4 dark:border-slate-800">
                        <img
                            v-if="item.avatar"
                            :src="item.avatar"
                            alt=""
                            class="size-9 rounded-full object-cover"
                        />
                        <div>
                            <p
                                class="text-sm font-semibold text-slate-950 dark:text-slate-50"
                            >
                                {{ item.title }}
                            </p>
                            <p
                                v-if="item.role"
                                class="text-xs text-slate-500 dark:text-slate-400"
                            >
                                {{ item.role }}
                            </p>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </section>
</template>
