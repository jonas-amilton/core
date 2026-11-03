<script setup lang="ts">
import type { Block } from '@modules/Page/resources/assets/js/types';

defineProps<{ block: Block }>();
</script>

<template>
    <section class="px-4 py-14 sm:px-6 sm:py-20">
        <p
            class="mx-auto max-w-3xl text-[17px] leading-[1.8] whitespace-pre-line text-slate-700 dark:text-slate-300"
        >
            {{ block.body }}
        </p>
    </section>
</template>
