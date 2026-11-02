<script setup lang="ts">
import { Link, router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import SiteHead from '@/components/SiteHead.vue';
import ProductCard from '../components/ProductCard.vue';
import type { CardProduct } from '../components/ProductCard.vue';
import SiteShell from '@modules/Page/resources/assets/js/components/SiteShell.vue';
import type { NavigationItem } from '@modules/Page/resources/assets/js/types';
import { useI18n } from '@/i18n';

type Category = {
    id: number;
    name: string;
    slug: string;
    parent_id: number | null;
    products_count: number | null;
};

type Paginator = {
    data: CardProduct[];
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
    filters: { sort: string; min: string | null; max: string | null };
    navigation: NavigationItem[];
}>();

const title = props.activeCategory
    ? `${t('catalog.title')} · ${props.activeCategory.name}`
    : t('catalog.title');

const page = usePage();
const sort = ref(props.filters.sort);
const min = ref(props.filters.min ?? '');
const max = ref(props.filters.max ?? '');

function applyFilters(): void {
    router.get(
        page.url.split('?')[0],
        {
            sort: sort.value !== 'default' ? sort.value : undefined,
            min: min.value || undefined,
            max: max.value || undefined,
        },
        { preserveState: true, preserveScroll: true },
    );
}
</script>

<template>
    <SiteHead :title="title" />

    <SiteShell :navigation="props.navigation">
        <section class="px-4 py-8 sm:px-6 sm:py-10">
            <h1
                class="text-2xl font-semibold tracking-tight text-slate-950 sm:text-3xl dark:text-slate-50"
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
                class="mt-5 flex flex-wrap items-center gap-2"
            >
                <Link
                    href="/produtos"
                    :class="[
                        'inline-flex h-9 items-center rounded-full px-4 text-sm font-medium transition-colors',
                        props.activeCategory === null
                            ? 'bg-brand text-white'
                            : 'bg-[#f5f5f5] text-slate-700 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300',
                    ]"
                >
                    {{ t('common.all') }}
                </Link>
                <Link
                    v-for="category in props.categories"
                    :key="category.id"
                    :href="`/produtos/c/${category.slug}`"
                    :class="[
                        'inline-flex h-9 items-center gap-1.5 rounded-full px-4 text-sm font-medium transition-colors',
                        props.activeCategory?.id === category.id
                            ? 'bg-brand text-white'
                            : 'bg-[#f5f5f5] text-slate-700 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300',
                    ]"
                >
                    {{ category.name }}
                    <span
                        v-if="category.products_count"
                        :class="
                            props.activeCategory?.id === category.id
                                ? 'text-white/60'
                                : 'text-slate-400'
                        "
                    >
                        {{ category.products_count }}
                    </span>
                </Link>
            </nav>

            <form
                class="mt-4 flex flex-wrap items-center justify-between gap-3"
                @submit.prevent="applyFilters"
            >
                <div class="flex flex-wrap items-center gap-2 text-sm">
                    <span class="text-slate-500 dark:text-slate-400">
                        {{ t('catalog.price') }}
                    </span>
                    <label class="sr-only" for="filter-min">
                        {{ t('catalog.minPrice') }}
                    </label>
                    <input
                        id="filter-min"
                        v-model="min"
                        type="number"
                        min="0"
                        :placeholder="t('catalog.minPrice')"
                        class="h-9 w-24 rounded-full border border-slate-200 bg-white px-3 text-sm tabular-nums focus-visible:border-brand focus-visible:ring-2 focus-visible:ring-brand/20 dark:border-slate-800 dark:bg-slate-900"
                    />
                    <span class="text-slate-400">–</span>
                    <label class="sr-only" for="filter-max">
                        {{ t('catalog.maxPrice') }}
                    </label>
                    <input
                        id="filter-max"
                        v-model="max"
                        type="number"
                        min="0"
                        :placeholder="t('catalog.maxPrice')"
                        class="h-9 w-24 rounded-full border border-slate-200 bg-white px-3 text-sm tabular-nums focus-visible:border-brand focus-visible:ring-2 focus-visible:ring-brand/20 dark:border-slate-800 dark:bg-slate-900"
                    />
                    <button
                        type="submit"
                        class="inline-flex h-9 items-center rounded-full border border-brand/30 px-4 text-sm font-medium text-brand-deep transition-colors hover:bg-brand hover:text-white dark:border-slate-700 dark:text-slate-200"
                    >
                        {{ t('catalog.apply') }}
                    </button>
                </div>

                <label
                    class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400"
                >
                    {{ t('catalog.sort') }}
                    <select
                        v-model="sort"
                        class="h-9 rounded-full border border-slate-200 bg-white px-3 text-sm text-slate-900 focus-visible:border-brand focus-visible:ring-2 focus-visible:ring-brand/20 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100"
                        @change="applyFilters"
                    >
                        <option value="default">
                            {{ t('catalog.sortDefault') }}
                        </option>
                        <option value="price_asc">
                            {{ t('catalog.sortPriceAsc') }}
                        </option>
                        <option value="price_desc">
                            {{ t('catalog.sortPriceDesc') }}
                        </option>
                        <option value="name">{{ t('catalog.sortName') }}</option>
                        <option value="newest">
                            {{ t('catalog.sortNewest') }}
                        </option>
                    </select>
                </label>
            </form>

            <ul class="mt-8 grid gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4">
                <li v-for="product in props.products.data" :key="product.id">
                    <ProductCard :product="product" />
                </li>
            </ul>

            <p
                v-if="props.products.data.length === 0"
                class="mt-10 rounded-xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center text-sm text-slate-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-400"
            >
                {{ t('catalog.empty') }}
            </p>

            <nav
                v-if="props.products.last_page > 1"
                class="mt-10 flex items-center justify-between text-sm"
                aria-label="Paginação"
            >
                <Link
                    v-if="props.products.prev_page_url"
                    :href="props.products.prev_page_url"
                    class="text-brand hover:underline dark:text-green-400"
                >
                    {{ t('common.previous') }}
                </Link>
                <span v-else class="text-slate-400">{{
                    t('common.previous')
                }}</span>

                <span class="text-xs text-slate-500">
                    {{
                        t('common.pageOf')
                            .replace(
                                ':current',
                                String(props.products.current_page),
                            )
                            .replace(':total', String(props.products.last_page))
                    }}
                </span>

                <Link
                    v-if="props.products.next_page_url"
                    :href="props.products.next_page_url"
                    class="text-brand hover:underline dark:text-green-400"
                >
                    {{ t('common.next') }}
                </Link>
                <span v-else class="text-slate-400">{{
                    t('common.next')
                }}</span>
            </nav>
        </section>
    </SiteShell>
</template>
