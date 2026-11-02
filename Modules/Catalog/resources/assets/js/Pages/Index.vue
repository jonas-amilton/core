<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Check, Plus } from '@lucide/vue';
import { ref } from 'vue';
import { store as addToCart } from '@/routes/quote/item';
import SiteShell from '@modules/Page/resources/assets/js/components/SiteShell.vue';
import type { NavigationItem } from '@modules/Page/resources/assets/js/types';
import { formatPrice } from '@/lib/format';
import { useI18n } from '@/i18n';

type Product = {
    id: number;
    name: string;
    slug: string;
    sku: string | null;
    price: string | null;
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

const added = ref<Record<number, boolean>>({});

function add(product: Product): void {
    router.post(
        addToCart.url(),
        { product_id: product.id, quantity: 1 },
        {
            preserveScroll: true,
            onSuccess: () => (added.value[product.id] = true),
        },
    );
}
</script>

<template>
    <Head :title="title" />

    <SiteShell :navigation="props.navigation">
        <section class="px-4 py-12 sm:px-6 sm:py-16">
            <h1
                class="text-3xl font-semibold tracking-tight text-slate-950 sm:text-4xl dark:text-slate-50"
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
                        'rounded-full border px-3.5 py-1.5 text-xs font-medium',
                        props.activeCategory === null
                            ? 'border-blue-200 bg-blue-50 text-blue-800 dark:border-slate-700 dark:bg-blue-600/10 dark:text-blue-300'
                            : 'border-slate-200 text-slate-600 hover:border-blue-300 dark:border-slate-800 dark:text-slate-400',
                    ]"
                >
                    {{ t('common.all') }}
                </Link>
                <Link
                    v-for="category in props.categories"
                    :key="category.id"
                    :href="`/produtos/c/${category.slug}`"
                    :class="[
                        'rounded-full border px-3.5 py-1.5 text-xs font-medium',
                        props.activeCategory?.id === category.id
                            ? 'border-blue-200 bg-blue-50 text-blue-800 dark:border-slate-700 dark:bg-blue-600/10 dark:text-blue-300'
                            : 'border-slate-200 text-slate-600 hover:border-blue-300 dark:border-slate-800 dark:text-slate-400',
                    ]"
                >
                    {{ category.name }}
                    <span v-if="category.products_count" class="text-slate-400">
                        ({{ category.products_count }})
                    </span>
                </Link>
            </nav>

            <ul class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <li
                    v-for="product in props.products.data"
                    :key="product.id"
                    class="flex flex-col overflow-hidden rounded-lg border border-slate-200 bg-white shadow-xs transition-colors hover:border-blue-300 dark:border-slate-800 dark:bg-slate-900 dark:hover:border-blue-500/40"
                >
                    <Link :href="`/produto/${product.slug}`" class="block">
                        <img
                            v-if="product.cover"
                            :src="product.cover"
                            alt=""
                            class="aspect-square w-full bg-white object-contain p-6"
                        />
                        <h2
                            class="px-4 pt-4 text-sm leading-snug font-semibold text-slate-900 dark:text-slate-100"
                        >
                            {{ product.name }}
                        </h2>
                    </Link>

                    <div class="flex flex-1 flex-col p-4">
                        <p
                            v-if="product.sku"
                            class="text-xs text-slate-400"
                        >
                            {{ t('catalog.sku') }}: {{ product.sku }}
                        </p>

                        <p
                            class="mt-2 text-lg font-semibold tracking-tight text-slate-950 tabular-nums dark:text-slate-50"
                        >
                            {{
                                formatPrice(product.price) ??
                                t('catalog.priceOnRequest')
                            }}
                        </p>

                        <button
                            type="button"
                            class="mt-3 inline-flex h-10 items-center justify-center gap-2 rounded-md bg-blue-800 px-4 text-sm font-medium text-white transition-colors hover:bg-blue-900 disabled:opacity-50"
                            :aria-label="`${t('catalog.addToCart')}: ${product.name}`"
                            @click="add(product)"
                        >
                            <Check
                                v-if="added[product.id]"
                                class="size-4"
                                aria-hidden="true"
                            />
                            <Plus v-else class="size-4" aria-hidden="true" />
                            {{
                                added[product.id]
                                    ? t('catalog.added')
                                    : t('catalog.addToCart')
                            }}
                        </button>
                    </div>
                </li>
            </ul>

            <p
                v-if="props.products.data.length === 0"
                class="mt-10 rounded-lg border border-dashed border-slate-300 bg-white px-6 py-16 text-center text-sm text-slate-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-400"
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
                    class="text-blue-700 hover:underline dark:text-blue-400"
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
                    class="text-blue-700 hover:underline dark:text-blue-400"
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
