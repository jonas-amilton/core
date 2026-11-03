<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft } from '@lucide/vue';
import ClientsBlock from '@modules/Site/resources/assets/js/Blocks/ClientsBlock.vue';
import CareerBlock from '@modules/Site/resources/assets/js/Blocks/CareerBlock.vue';
import CategoriesBlock from '@modules/Site/resources/assets/js/Blocks/CategoriesBlock.vue';
import ContactBlock from '@modules/Site/resources/assets/js/Blocks/ContactBlock.vue';
import CtaBlock from '@modules/Site/resources/assets/js/Blocks/CtaBlock.vue';
import FaqBlock from '@modules/Site/resources/assets/js/Blocks/FaqBlock.vue';
import GridBlock from '@modules/Site/resources/assets/js/Blocks/GridBlock.vue';
import HeroBlock from '@modules/Site/resources/assets/js/Blocks/HeroBlock.vue';
import ImageBlock from '@modules/Site/resources/assets/js/Blocks/ImageBlock.vue';
import NewsletterBlock from '@modules/Site/resources/assets/js/Blocks/NewsletterBlock.vue';
import PostsBlock from '@modules/Site/resources/assets/js/Blocks/PostsBlock.vue';
import ProductsBlock from '@modules/Site/resources/assets/js/Blocks/ProductsBlock.vue';
import ServicesBlock from '@modules/Site/resources/assets/js/Blocks/ServicesBlock.vue';
import TestimonialsBlock from '@modules/Site/resources/assets/js/Blocks/TestimonialsBlock.vue';
import TextBlock from '@modules/Site/resources/assets/js/Blocks/TextBlock.vue';
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
    services: ServicesBlock,
    testimonials: TestimonialsBlock,
    faq: FaqBlock,
    clients: ClientsBlock,
    posts: PostsBlock,
    products: ProductsBlock,
    categories: CategoriesBlock,
    contact: ContactBlock,
    career: CareerBlock,
    newsletter: NewsletterBlock,
};

const visibleBlocks = (blocks: Block[]): Block[] =>
    blocks.filter((block) => !block.hidden);
</script>

<template>
    <Head :title="`Pré-visualização: ${page.title}`" />

    <div class="min-h-screen bg-white dark:bg-stone-950">
        <div
            class="sticky top-0 z-10 flex items-center justify-between gap-3 border-b border-stone-200 bg-background px-4 py-2 dark:border-stone-800"
        >
            <Link
                :href="edit(page.id)"
                class="inline-flex items-center gap-1.5 rounded-sm px-2 py-1 text-sm text-stone-600 hover:text-stone-900 dark:text-stone-400 dark:hover:text-stone-100"
            >
                <ArrowLeft class="size-4" aria-hidden="true" />
                Voltar ao editor
            </Link>
            <div class="flex items-center gap-2 text-xs">
                <span class="text-stone-500 dark:text-stone-400">
                    {{ statusLabels[page.status] }}
                </span>
                <span class="text-stone-400 dark:text-stone-600">·</span>
                <span class="font-mono text-stone-500 dark:text-stone-400">
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
                class="px-6 py-20 text-center text-sm text-stone-500 dark:text-stone-400"
            >
                Esta página não tem blocos visíveis.
            </p>
        </main>
    </div>
</template>
