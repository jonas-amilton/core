<script setup lang="ts">
import type { Block } from '../types';

defineProps<{ block: Block }>();
</script>

<template>
    <section class="px-6 py-10 sm:px-10">
        <div
            class="flex flex-col items-start justify-between gap-4 rounded-xl bg-slate-900 px-6 py-8 text-white sm:flex-row sm:items-center dark:bg-slate-800"
        >
            <div>
                <p class="text-lg font-semibold">{{ block.title }}</p>
                <p v-if="block.description" class="mt-1 text-sm text-slate-300">
                    {{ block.description }}
                </p>
            </div>
            <span
                v-if="block.button_label"
                class="inline-flex h-10 shrink-0 items-center rounded-md bg-indigo-600 px-5 text-sm font-medium text-white"
            >
                {{ block.button_label }}
            </span>
        </div>
    </section>
</template>
