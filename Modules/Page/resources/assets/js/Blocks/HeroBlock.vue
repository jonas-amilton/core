<script setup lang="ts">
import type { Block } from '../types';

defineProps<{ block: Block }>();
</script>

<template>
    <section
        class="bg-linear-to-br from-indigo-500 via-indigo-600 to-violet-600 px-6 py-12 text-white sm:px-10 sm:py-16"
    >
        <h1
            class="text-3xl font-semibold tracking-tight text-balance sm:text-4xl"
        >
            {{ block.title }}
        </h1>
        <p
            v-if="block.subtitle"
            class="mt-3 max-w-2xl text-sm text-indigo-100 sm:text-base"
        >
            {{ block.subtitle }}
        </p>
        <span
            v-if="block.button_label"
            class="mt-6 inline-flex h-10 items-center rounded-md bg-white px-5 text-sm font-medium text-indigo-700"
        >
            {{ block.button_label }}
        </span>
    </section>
</template>
