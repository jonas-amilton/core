<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft } from '@lucide/vue';
import CtaBlock from '../Blocks/CtaBlock.vue';
import GridBlock from '../Blocks/GridBlock.vue';
import HeroBlock from '../Blocks/HeroBlock.vue';
import ImageBlock from '../Blocks/ImageBlock.vue';
import TextBlock from '../Blocks/TextBlock.vue';
import { statusLabels } from '../blocks';
import type { Block, PageRecord } from '../types';
import { edit } from '@/routes/page';

defineProps<{
    page: PageRecord;
}>();

const renderers = {
    hero: HeroBlock,
    text: TextBlock,
    grid: GridBlock,
    image: ImageBlock,
    cta: CtaBlock,
};

const visibleBlocks = (blocks: Block[]): Block[] =>
    blocks.filter((block) => !block.hidden);
</script>

<template>
    <Head :title="`Pré-visualização: ${page.title}`" />

    <div class="min-h-screen bg-white dark:bg-slate-950">
        <div
            class="sticky top-0 z-10 flex items-center justify-between gap-3 border-b border-slate-200 bg-white/90 px-4 py-2 backdrop-blur-md dark:border-slate-800 dark:bg-slate-950/90"
        >
            <Link
                :href="edit(page.id)"
                class="inline-flex items-center gap-1.5 rounded-md px-2 py-1 text-sm text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-100"
            >
                <ArrowLeft class="size-4" aria-hidden="true" />
                Voltar ao editor
            </Link>
            <div class="flex items-center gap-2 text-xs">
                <span class="text-slate-500 dark:text-slate-400">
                    {{ statusLabels[page.status] }}
                </span>
                <span class="text-slate-400 dark:text-slate-600">·</span>
                <span class="font-mono text-slate-500 dark:text-slate-400">
                    /{{ page.slug }}
                </span>
            </div>
        </div>

        <main class="mx-auto max-w-5xl overflow-hidden">
            <div v-for="block in visibleBlocks(page.blocks)" :key="block.id">
                <component :is="renderers[block.type]" :block="block" />
            </div>

            <p
                v-if="visibleBlocks(page.blocks).length === 0"
                class="px-6 py-20 text-center text-sm text-slate-500 dark:text-slate-400"
            >
                Esta página ainda não tem blocos visíveis.
            </p>
        </main>
    </div>
</template>
