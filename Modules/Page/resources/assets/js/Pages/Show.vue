<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import CtaBlock from '../Blocks/CtaBlock.vue';
import GridBlock from '../Blocks/GridBlock.vue';
import HeroBlock from '../Blocks/HeroBlock.vue';
import ImageBlock from '../Blocks/ImageBlock.vue';
import TextBlock from '../Blocks/TextBlock.vue';
import type { Block, NavigationItem, PageRecord } from '../types';

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
};

const siteName = computed(
    () => (usePage().props.name as string | undefined) ?? 'CoreCMS',
);

const isCurrent = (item: NavigationItem): boolean =>
    item.slug === props.page.slug;

const visibleBlocks = computed<Block[]>(() =>
    props.page.blocks.filter((block) => !block.hidden),
);

const currentYear = new Date().getFullYear();
</script>

<template>
    <Head :title="page.title" />

    <div
        class="min-h-screen bg-white text-slate-900 antialiased dark:bg-slate-950 dark:text-slate-100"
    >
        <header
            class="sticky top-0 z-40 border-b border-slate-200/80 bg-white/90 backdrop-blur-md dark:border-slate-800/80 dark:bg-slate-950/90"
        >
            <div
                class="mx-auto flex h-16 w-full max-w-5xl items-center justify-between gap-4 px-4 sm:px-6"
            >
                <Link
                    :href="'/'"
                    class="text-base font-semibold tracking-tight text-slate-900 dark:text-slate-100"
                >
                    {{ siteName }}
                </Link>

                <nav
                    v-if="navigation.length > 0"
                    aria-label="Páginas do site"
                    class="hidden items-center gap-1 sm:flex"
                >
                    <a
                        v-for="item in navigation"
                        :key="item.slug"
                        :href="item.url"
                        :aria-current="isCurrent(item) ? 'page' : undefined"
                        :class="[
                            'rounded-md px-3 py-1.5 text-sm transition-colors',
                            isCurrent(item)
                                ? 'bg-indigo-50 font-medium text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400'
                                : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-100',
                        ]"
                    >
                        {{ item.title }}
                    </a>
                </nav>

                <a
                    href="/admin"
                    class="rounded-md px-2 py-1 text-xs font-medium text-slate-500 hover:text-indigo-600 dark:text-slate-500 dark:hover:text-indigo-400"
                >
                    Painel
                </a>
            </div>
        </header>

        <main class="mx-auto w-full max-w-5xl">
            <div v-for="block in visibleBlocks" :key="block.id">
                <component :is="renderers[block.type]" :block="block" />
            </div>

            <p
                v-if="visibleBlocks.length === 0"
                class="px-6 py-24 text-center text-sm text-slate-500 dark:text-slate-400"
            >
                Esta página ainda não tem conteúdo.
            </p>
        </main>

        <footer class="border-t border-slate-200 dark:border-slate-800">
            <div
                class="mx-auto flex w-full max-w-5xl flex-col items-center justify-between gap-2 px-4 py-6 text-xs text-slate-500 sm:flex-row sm:px-6 dark:text-slate-400"
            >
                <p>© {{ currentYear }} {{ siteName }}</p>
                <p class="text-slate-400 dark:text-slate-500">
                    Feito com CoreCMS
                </p>
            </div>
        </footer>
    </div>
</template>
