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

        <div class="mt-6 grid gap-2">
            <details
                v-for="(item, index) in block.items ?? []"
                :key="index"
                class="rounded-xl border border-slate-200 p-4 dark:border-slate-800"
            >
                <summary
                    class="cursor-pointer text-sm font-medium text-slate-900 marker:content-none dark:text-slate-100"
                >
                    {{ item.title }}
                </summary>
                <p
                    class="mt-2 text-sm whitespace-pre-line text-slate-600 dark:text-slate-400"
                >
                    {{ item.description }}
                </p>
            </details>
        </div>
    </section>
</template>
