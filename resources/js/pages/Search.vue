<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Search } from '@lucide/vue';
import SiteHead from '@/components/SiteHead.vue';
import SiteShell from '@modules/Page/resources/assets/js/components/SiteShell.vue';
import type { NavigationItem } from '@modules/Page/resources/assets/js/types';
import { formatPrice } from '@/lib/format';
import { useI18n } from '@/i18n';

type ProductResult = {
    id: number;
    name: string;
    slug: string;
    sku: string | null;
    price: string | null;
    cover: string | null;
};

type PostResult = {
    title: string;
    slug: string;
    excerpt: string | null;
    cover: string | null;
};

type PageResult = {
    title: string;
    url: string;
};

const { t } = useI18n();

const props = defineProps<{
    term: string;
    products: ProductResult[];
    posts: PostResult[];
    pages: PageResult[];
    navigation: NavigationItem[];
}>();

const total = props.products.length + props.posts.length + props.pages.length;
</script>

<template>
    <SiteHead
        :title="props.term ? `${t('search.title')}: ${props.term}` : t('search.title')"
    />

    <SiteShell :navigation="props.navigation">
        <section class="px-4 py-12 sm:px-6 sm:py-16">
            <h1
                class="text-3xl font-semibold tracking-tight text-slate-950 sm:text-4xl dark:text-slate-50"
            >
                {{ t('search.title') }}
            </h1>

            <form
                action="/busca"
                method="get"
                role="search"
                class="mt-6 flex max-w-xl gap-2"
            >
                <label class="sr-only" for="search-page-input">
                    {{ t('search.placeholder') }}
                </label>
                <input
                    id="search-page-input"
                    type="search"
                    name="q"
                    :value="props.term"
                    :placeholder="t('search.placeholder')"
                    class="h-11 flex-1 rounded-md border border-slate-300 bg-white px-3.5 text-sm text-slate-900 placeholder:text-slate-500 focus-visible:border-brand focus-visible:ring-2 focus-visible:ring-brand/20 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                />
                <button
                    type="submit"
                    class="inline-flex h-11 items-center gap-2 rounded-full bg-brand px-5 text-sm font-medium text-white hover:bg-brand-deep"
                >
                    <Search class="size-4" aria-hidden="true" />
                    {{ t('search.submit') }}
                </button>
            </form>

            <p
                v-if="props.term"
                class="mt-6 text-sm text-slate-600 dark:text-slate-400"
            >
                {{
                    t('search.resultsFor').replace(':term', props.term)
                }}
                — {{ total }}
            </p>

            <p
                v-if="props.term && total === 0"
                class="mt-8 rounded-lg border border-dashed border-slate-300 bg-white px-6 py-16 text-center text-sm text-slate-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-400"
            >
                {{ t('search.empty') }}
            </p>

            <section v-if="props.products.length > 0" class="mt-10">
                <h2
                    class="text-xl font-semibold tracking-tight text-slate-950 dark:text-slate-50"
                >
                    {{ t('search.products') }}
                </h2>
                <ul class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <li
                        v-for="product in props.products"
                        :key="product.id"
                        class="rounded-lg border border-slate-200 bg-white p-4 shadow-xs dark:border-slate-800 dark:bg-slate-900"
                    >
                        <Link
                            :href="`/produto/${product.slug}`"
                            class="flex items-center gap-3"
                        >
                            <img
                                v-if="product.cover"
                                :src="product.cover"
                                alt=""
                                class="size-16 shrink-0 rounded-md bg-white object-contain p-1"
                            />
                            <span class="min-w-0">
                                <span
                                    class="block text-sm font-medium text-slate-900 hover:text-brand dark:text-slate-100"
                                >
                                    {{ product.name }}
                                </span>
                                <span
                                    class="mt-1 block text-sm font-semibold text-slate-950 tabular-nums dark:text-slate-50"
                                >
                                    {{
                                        formatPrice(product.price) ??
                                        t('catalog.priceOnRequest')
                                    }}
                                </span>
                            </span>
                        </Link>
                    </li>
                </ul>
            </section>

            <section v-if="props.posts.length > 0" class="mt-10">
                <h2
                    class="text-xl font-semibold tracking-tight text-slate-950 dark:text-slate-50"
                >
                    {{ t('search.posts') }}
                </h2>
                <ul class="mt-4 divide-y divide-slate-200 dark:divide-slate-800">
                    <li
                        v-for="post in props.posts"
                        :key="post.slug"
                        class="py-4"
                    >
                        <Link
                            :href="`/blog/${post.slug}`"
                            class="text-base font-medium text-slate-900 hover:text-brand dark:text-slate-100"
                        >
                            {{ post.title }}
                        </Link>
                        <p
                            v-if="post.excerpt"
                            class="mt-1 line-clamp-2 text-sm text-slate-600 dark:text-slate-400"
                        >
                            {{ post.excerpt }}
                        </p>
                    </li>
                </ul>
            </section>

            <section v-if="props.pages.length > 0" class="mt-10">
                <h2
                    class="text-xl font-semibold tracking-tight text-slate-950 dark:text-slate-50"
                >
                    {{ t('search.pages') }}
                </h2>
                <ul class="mt-4 divide-y divide-slate-200 dark:divide-slate-800">
                    <li v-for="page in props.pages" :key="page.url" class="py-3">
                        <Link
                            :href="page.url"
                            class="text-sm font-medium text-slate-900 hover:text-brand dark:text-slate-100"
                        >
                            {{ page.title }}
                        </Link>
                    </li>
                </ul>
            </section>
        </section>
    </SiteShell>
</template>
