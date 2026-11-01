<script setup lang="ts">
import { ImageIcon } from '@lucide/vue';
import type { Block } from '../types';

defineProps<{ block: Block }>();
</script>

<template>
    <figure class="px-6 py-10 sm:px-10">
        <img
            v-if="block.src"
            :src="block.src"
            :alt="block.alt ?? ''"
            class="aspect-video w-full rounded-xl border border-slate-200 object-cover dark:border-slate-800"
        />
        <div
            v-else
            class="flex aspect-video w-full items-center justify-center rounded-xl border border-dashed border-slate-300 text-slate-400 dark:border-slate-700 dark:text-slate-500"
        >
            <ImageIcon class="size-8" aria-hidden="true" />
        </div>
        <figcaption
            v-if="block.caption"
            class="mt-3 text-center text-xs text-slate-500 dark:text-slate-400"
        >
            {{ block.caption }}
        </figcaption>
    </figure>
</template>
