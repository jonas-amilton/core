<script setup lang="ts">
import type { Block } from '../types';

defineProps<{ block: Block }>();
</script>

<template>
    <section class="px-6 py-10 sm:px-10">
        <p
            class="max-w-3xl text-base leading-relaxed whitespace-pre-line text-slate-700 dark:text-slate-300"
        >
            {{ block.body }}
        </p>
    </section>
</template>
