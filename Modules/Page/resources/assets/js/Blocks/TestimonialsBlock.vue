<script setup lang="ts">
import type { Block } from '../types';

defineProps<{
    block: Block;
}>();
</script>

<template>
    <section class="px-4 py-12 sm:px-6">
        <h2
            v-if="block.title"
            class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-slate-100"
        >
            {{ block.title }}
        </h2>

        <ul class="mt-6 grid gap-4 sm:grid-cols-2">
            <li
                v-for="(item, index) in block.items ?? []"
                :key="index"
                class="rounded-xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900"
            >
                <p
                    class="text-sm whitespace-pre-line text-slate-700 dark:text-slate-300"
                >
                    “{{ item.description }}”
                </p>
                <div class="mt-4 flex items-center gap-3">
                    <img
                        v-if="item.avatar"
                        :src="item.avatar"
                        alt=""
                        class="size-10 rounded-full object-cover"
                    />
                    <div>
                        <p
                            class="text-sm font-medium text-slate-900 dark:text-slate-100"
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
    </section>
</template>
