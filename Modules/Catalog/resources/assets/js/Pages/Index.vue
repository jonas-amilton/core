<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { Heart } from '@lucide/vue';
import { ref } from 'vue';
import { store as addToCart } from '@/routes/quote/item';
import SiteHead from '@/components/SiteHead.vue';
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
    stock: number | null;
    rating: number | null;
    review_count: number;
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

function favorite(product: Product): void {
    router.post(`/favoritos/${product.id}`, {}, { preserveScroll: true });
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

            <ul class="mt-8 grid gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4">
                <li
                    v-for="product in props.products.data"
                    :key="product.id"
                    class="group flex flex-col"
                >
                    <div
                        class="relative aspect-square overflow-hidden rounded-xl bg-[#f5f5f5] dark:bg-slate-800"
                    >
                        <Link :href="`/produto/${product.slug}`" class="block h-full">
                            <img
                                v-if="product.cover"
                                :src="product.cover"
                                alt=""
                                class="h-full w-full object-contain p-6 mix-blend-multiply transition-transform duration-300 motion-safe:group-hover:scale-[1.03] dark:mix-blend-normal"
                            />
                        </Link>

                        <button
                            type="button"
                            class="absolute top-3 right-3 flex size-9 items-center justify-center rounded-full bg-white text-slate-500 shadow-sm transition-colors hover:text-red-500"
                            :aria-label="`${t('catalog.favorite')}: ${product.name}`"
                            @click="favorite(product)"
                        >
                            <Heart class="size-4" aria-hidden="true" />
                        </button>
                    </div>

                    <div class="mt-4 flex items-start justify-between gap-3">
                        <Link
                            :href="`/produto/${product.slug}`"
                            class="text-[15px] leading-snug font-semibold text-slate-950 hover:text-brand dark:text-slate-50"
                        >
                            {{ product.name }}
                        </Link>
                        <p
                            class="shrink-0 text-[15px] font-semibold text-slate-950 tabular-nums dark:text-slate-50"
                        >
                            {{
                                formatPrice(product.price) ??
                                t('catalog.priceOnRequest')
                            }}
                        </p>
                    </div>

                    <div class="flex-1">
                    <p
                        v-if="product.sku"
                        class="mt-1 text-xs text-slate-500 dark:text-slate-400"
                    >
                        {{ t('catalog.sku') }}: {{ product.sku }}
                    </p>

                    <p
                        v-if="product.rating"
                        class="mt-1 text-xs text-brand dark:text-green-400"
                        :aria-label="`${product.rating} / 5`"
                    >
                        <span aria-hidden="true">
                            {{ '★'.repeat(Math.round(product.rating)) }}
                        </span>
                        <span class="ml-1 text-slate-500 dark:text-slate-400">
                            ({{ product.review_count }})
                        </span>
                    </p>
                    </div>

                    <button
                        v-if="product.stock !== 0"
                        type="button"
                        :class="[
                            'mt-4 inline-flex h-10 w-full items-center justify-center gap-2 rounded-full border text-sm font-medium transition-colors',
                            added[product.id]
                                ? 'border-brand bg-brand text-white'
                                : 'border-brand/30 text-brand-deep hover:border-brand hover:bg-brand hover:text-white dark:border-slate-700 dark:text-slate-200',
                        ]"
                        :aria-label="`${t('catalog.addToCart')}: ${product.name}`"
                        @click="add(product)"
                    >
                        {{
                            added[product.id]
                                ? t('catalog.added')
                                : t('catalog.addToCart')
                        }}
                    </button>
                    <p
                        v-else
                        class="mt-auto inline-flex h-10 items-center justify-center rounded-full border border-slate-200 text-sm font-medium text-slate-500 dark:border-slate-700 dark:text-slate-400"
                    >
                        {{ t('catalog.outOfStock') }}
                    </p>
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
