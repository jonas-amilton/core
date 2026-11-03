<script setup lang="ts">
import { computed } from 'vue';
import ClientsBlock from '../Blocks/ClientsBlock.vue';
import CareerBlock from '../Blocks/CareerBlock.vue';
import CategoriesBlock from '../Blocks/CategoriesBlock.vue';
import ContactBlock from '../Blocks/ContactBlock.vue';
import CtaBlock from '../Blocks/CtaBlock.vue';
import FaqBlock from '../Blocks/FaqBlock.vue';
import GridBlock from '../Blocks/GridBlock.vue';
import HeroBlock from '../Blocks/HeroBlock.vue';
import ImageBlock from '../Blocks/ImageBlock.vue';
import NewsletterBlock from '../Blocks/NewsletterBlock.vue';
import PostsBlock from '../Blocks/PostsBlock.vue';
import ProductsBlock from '../Blocks/ProductsBlock.vue';
import ServicesBlock from '../Blocks/ServicesBlock.vue';
import TestimonialsBlock from '../Blocks/TestimonialsBlock.vue';
import TextBlock from '../Blocks/TextBlock.vue';
import SiteShell from '../components/SiteShell.vue';
import SiteHead from '@/components/SiteHead.vue';
import type { Block, NavigationItem, PageRecord } from '@modules/Page/resources/assets/js/types';

const props = defineProps<{
    page: PageRecord;
    navigation: NavigationItem[];
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

const visibleBlocks = computed<Block[]>(() =>
    props.page.blocks.filter((block) => !block.hidden),
);

const seoDescription = computed<string | null>(() => {
    for (const block of visibleBlocks.value) {
        const text = block.subtitle ?? block.description ?? block.body;

        if (text) {
            return text;
        }
    }

    return null;
});

const seoImage = computed<string | null>(
    () => visibleBlocks.value.find((block) => block.src)?.src ?? null,
);
</script>

<template>
    <SiteHead
        :title="page.title"
        :description="seoDescription"
        :image="seoImage"
    />

    <SiteShell :navigation="navigation">
        <div v-for="block in visibleBlocks" :key="block.id">
            <component :is="renderers[block.type]" :block="block" />
        </div>

        <p
            v-if="visibleBlocks.length === 0"
            class="px-6 py-24 text-center text-sm text-slate-600 dark:text-slate-400"
        >
            Esta página ainda não tem conteúdo.
        </p>
    </SiteShell>
</template>
