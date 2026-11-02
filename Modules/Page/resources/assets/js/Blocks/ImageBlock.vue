<script setup lang="ts">
import { ImageIcon } from '@lucide/vue';
import type { Block } from '../types';

defineProps<{ block: Block }>();
</script>

<template>
    <figure class="px-4 py-10 sm:px-6">
        <div class="mx-auto max-w-5xl">
            <div
                v-if="block.src"
                class="overflow-hidden rounded-lg border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900"
            >
                <img
                    :src="block.src"
                    :alt="block.alt ?? ''"
                    class="aspect-video w-full object-cover"
                />
            </div>
            <div
                v-else
                class="flex aspect-video w-full items-center justify-center rounded-lg border border-dashed border-slate-300 text-slate-400 dark:border-slate-700 dark:text-slate-500"
            >
                <ImageIcon class="size-8" aria-hidden="true" />
            </div>
            <figcaption
                v-if="block.caption"
                class="mt-3 text-center text-xs text-slate-500 dark:text-slate-400"
            >
                {{ block.caption }}
            </figcaption>
        </div>
    </figure>
</template>
