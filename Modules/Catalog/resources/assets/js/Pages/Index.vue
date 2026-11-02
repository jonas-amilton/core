<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import SiteShell from '@modules/Page/resources/assets/js/components/SiteShell.vue';
import type { NavigationItem } from '@modules/Page/resources/assets/js/types';
import { useI18n } from '@/i18n';

type Product = {
    id: number;
    name: string;
    slug: string;
    sku: string | null;
    cover: string | null;
};

type Category = {
    id: number;
    name: string;
    slug: string;
    parent_id: number | null;
    products_count: number | null;
};

type Paginator = {
    data: Product[];
    current_page: number;
    last_page: number;
    prev_page_url: string | null;
    next_page_url: string | null;
};

const { t } = useI18n();

const props = defineProps<{
    products: Paginator;
    categories: Category[];
    activeCategory: Category | null;
    navigation: NavigationItem[];
}>();

const title = props.activeCategory
    ? `${t('catalog.title')} · ${props.activeCategory.name}`
    : t('catalog.title');
</script>

<template>
    <Head :title="title" />

    <SiteShell :navigation="props.navigation">
        <section class="px-4 py-10 sm:px-6">
            <h1
                class="text-3xl font-semibold tracking-tight text-slate-900 dark:text-slate-100"
            >
                {{
                    props.activeCategory
                        ? props.activeCategory.name
                        : t('catalog.title')
                }}
            </h1>

            <nav
                v-if="props.categories.length > 0"
                :aria-label="t('catalog.categoriesAria')"
                class="mt-4 flex flex-wrap gap-2"
            >
                <Link
                    href="/produtos"
                    :class="[
                        'rounded-full border px-3 py-1 text-xs font-medium',
                        props.activeCategory === null
                            ? 'border-indigo-200 bg-indigo-50 text-indigo-700 dark:border-indigo-500/30 dark:bg-indigo-500/10 dark:text-indigo-300'
                            : 'border-slate-200 text-slate-600 hover:border-indigo-300 dark:border-slate-800 dark:text-slate-400',
                    ]"
                >
                    {{ t('common.all') }}
                </Link>
                <Link
                    v-for="category in props.categories"
                    :key="category.id"
                    :href="`/produtos/c/${category.slug}`"
                    :class="[
                        'rounded-full border px-3 py-1 text-xs font-medium',
                        props.activeCategory?.id === category.id
                            ? 'border-indigo-200 bg-indigo-50 text-indigo-700 dark:border-indigo-500/30 dark:bg-indigo-500/10 dark:text-indigo-300'
                            : 'border-slate-200 text-slate-600 hover:border-indigo-300 dark:border-slate-800 dark:text-slate-400',
                    ]"
                >
                    {{ category.name }}
                    <span
                        v-if="category.products_count"
                        class="text-slate-400"
                    >
                        ({{ category.products_count }})
                    </span>
                </Link>
            </nav>

            <ul class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <li
                    v-for="product in props.products.data"
                    :key="product.id"
                    class="overflow-hidden rounded-xl border border-slate-200 transition-colors hover:border-indigo-300 dark:border-slate-800 dark:hover:border-indigo-500/40"
                >
                    <Link :href="`/produto/${product.slug}`" class="block">
                        <img
                            v-if="product.cover"
                            :src="product.cover"
                            alt=""
                            class="aspect-square w-full bg-white object-contain p-6"
                        />
                        <div class="border-t border-slate-100 p-4 dark:border-slate-800">
                            <h2
                                class="text-sm font-semibold text-slate-900 dark:text-slate-100"
                            >
                                {{ product.name }}
                            </h2>
                            <p v-if="product.sku" class="mt-1 text-xs text-slate-400">
                                {{ t('catalog.sku') }}: {{ product.sku }}
                            </p>
                        </div>
                    </Link>
                </li>
            </ul>

            <p
                v-if="props.products.data.length === 0"
                class="mt-10 rounded-xl border border-dashed border-slate-300 px-6 py-16 text-center text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400"
            >
                {{ t('catalog.empty') }}
            </p>

            <nav
                v-if="props.products.last_page > 1"
                class="mt-8 flex items-center justify-between text-sm"
                aria-label="Paginação"
            >
                <Link
                    v-if="props.products.prev_page_url"
                    :href="props.products.prev_page_url"
                    class="text-indigo-600 hover:underline dark:text-indigo-400"
                >
                    {{ t('common.previous') }}
                </Link>
                <span v-else class="text-slate-400">{{ t('common.previous') }}</span>

                <span class="text-xs text-slate-500">
                    {{
                        t('common.pageOf')
                            .replace(':current', String(props.products.current_page))
                            .replace(':total', String(props.products.last_page))
                    }}
                </span>

                <Link
                    v-if="props.products.next_page_url"
                    :href="props.products.next_page_url"
                    class="text-indigo-600 hover:underline dark:text-indigo-400"
                >
                    {{ t('common.next') }}
                </Link>
                <span v-else class="text-slate-400">{{ t('common.next') }}</span>
            </nav>
        </section>
    </SiteShell>
</template>
