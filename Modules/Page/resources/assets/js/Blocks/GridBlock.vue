<script setup lang="ts">
import type { Block } from '../types';

defineProps<{ block: Block }>();
</script>

<template>
    <section class="px-6 py-10 sm:px-10">
        <div class="grid gap-4 sm:grid-cols-3">
            <div
                v-for="(item, index) in block.items ?? []"
                :key="index"
                class="rounded-xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900"
            >
                <p
                    class="text-sm font-semibold text-slate-900 dark:text-slate-100"
                >
                    {{ item.title }}
                </p>
                <p
                    v-if="item.description"
                    class="mt-1.5 text-sm text-slate-600 dark:text-slate-400"
                >
                    {{ item.description }}
                </p>
            </div>
        </div>
    </section>
</template>
