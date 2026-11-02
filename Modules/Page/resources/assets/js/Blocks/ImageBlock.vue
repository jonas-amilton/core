<script setup lang="ts">
import { ImageIcon } from '@lucide/vue';
import type { Block } from '../types';

defineProps<{ block: Block }>();
</script>

<template>
    <figure class="px-4 py-10 sm:px-6">
        <div class="w-full">
            <img
                v-if="block.src"
                :src="block.src"
                :alt="block.alt ?? ''"
                class="aspect-video w-full border border-stone-200 object-cover dark:border-stone-800"
            />
            <div
                v-else
                class="flex aspect-video w-full items-center justify-center border border-dashed border-stone-300 text-stone-400 dark:border-stone-700 dark:text-stone-500"
            >
                <ImageIcon class="size-8" aria-hidden="true" />
            </div>
            <figcaption
                v-if="block.caption"
                class="mt-3 text-[11px] tracking-[0.12em] text-stone-500 uppercase dark:text-stone-400"
            >
                {{ block.caption }}
            </figcaption>
        </div>
    </figure>
</template>
