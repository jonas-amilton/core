<script setup lang="ts">
import type { Block } from '../types';

defineProps<{
    block: Block;
}>();

const sectionClass = 'px-4 py-12 sm:px-6';
</script>

<template>
    <section :class="sectionClass">
        <h2
            v-if="block.title"
            class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-slate-100"
        >
            {{ block.title }}
        </h2>
        <p
            v-if="block.subtitle"
            class="mt-1 text-slate-600 dark:text-slate-400"
        >
            {{ block.subtitle }}
        </p>

        <ul class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <li v-for="item in block.items ?? []" :key="item.title">
                <a
                    :href="item.url ?? '#'"
                    class="flex h-full items-start gap-3 rounded-xl border border-slate-200 p-4 transition-colors hover:border-indigo-300 dark:border-slate-800 dark:hover:border-indigo-500/40"
                >
                    <img
                        v-if="item.icon"
                        :src="item.icon"
                        alt=""
                        class="size-10 shrink-0 object-contain"
                    />
                    <span
                        class="text-sm font-medium text-slate-900 dark:text-slate-100"
                    >
                        {{ item.title }}
                    </span>
                </a>
            </li>
        </ul>

        <p
            v-if="(block.items ?? []).length === 0"
            class="mt-4 text-sm text-slate-500 dark:text-slate-400"
        >
            Nenhuma área publicada. Marque páginas como "Área de atuação" no
            editor.
        </p>
    </section>
</template>
