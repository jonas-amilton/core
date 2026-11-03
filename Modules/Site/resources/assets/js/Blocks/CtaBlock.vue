<script setup lang="ts">
import { ArrowRight } from '@lucide/vue';
import type { Block } from '@modules/Page/resources/assets/js/types';

defineProps<{ block: Block }>();
</script>

<template>
    <section
        class="px-4 py-6 sm:px-6"
    >
        <div
            class="mx-auto flex max-w-6xl flex-col gap-6 rounded-2xl bg-brand-deep px-6 py-10 text-white sm:flex-row sm:items-center sm:justify-between sm:px-10"
        >
            <div class="max-w-2xl">
                <p
                    class="text-2xl font-semibold tracking-tight sm:text-3xl"
                >
                    {{ block.title }}
                </p>
                <p
                    v-if="block.description"
                    class="mt-2 text-sm leading-relaxed text-slate-300 sm:text-base"
                >
                    {{ block.description }}
                </p>
            </div>

            <a
                v-if="block.button_label"
                :href="block.button_href || '#'"
                class="inline-flex h-11 shrink-0 items-center justify-center gap-2 rounded-md bg-brand px-5 text-sm font-medium text-white transition-colors hover:bg-green-700"
            >
                {{ block.button_label }}
                <ArrowRight class="size-4" aria-hidden="true" />
            </a>
        </div>
    </section>
</template>
