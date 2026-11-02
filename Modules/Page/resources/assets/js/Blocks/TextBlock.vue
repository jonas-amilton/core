<script setup lang="ts">
import type { Block } from '../types';

defineProps<{ block: Block }>();
</script>

<template>
    <section class="px-4 py-14 sm:px-6 sm:py-20">
        <p
            class="mx-auto max-w-3xl text-[17px] leading-[1.8] whitespace-pre-line text-stone-700 dark:text-stone-300"
        >
            {{ block.body }}
        </p>
    </section>
</template>
