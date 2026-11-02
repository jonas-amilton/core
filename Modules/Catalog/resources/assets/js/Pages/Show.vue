<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Check, Plus } from '@lucide/vue';
import { ref } from 'vue';
import { store as addToQuote } from '@/routes/quote/item';
import { useI18n } from '@/i18n';
import SiteShell from '@modules/Page/resources/assets/js/components/SiteShell.vue';
import type { NavigationItem } from '@modules/Page/resources/assets/js/types';

type Product = {
    id: number;
    name: string;
    slug: string;
    sku: string | null;
    description: string | null;
    images: string[];
    categories: { name: string; slug: string }[];
};

type RelatedProduct = {
    title: string;
    description: string | null;
    url: string;
    cover: string | null;
};

const { t } = useI18n();

const props = defineProps<{
    product: Product;
    related: RelatedProduct[];
    navigation: NavigationItem[];
}>();

const activeImage = ref(0);
const added = ref(false);

function addProduct(): void {
    router.post(
        addToQuote.url(),
        { product_id: props.product.id, quantity: 1 },
        { preserveScroll: true, onSuccess: () => (added.value = true) },
    );
}
</script>

<template>
    <Head :title="props.product.name" />

    <SiteShell :navigation="props.navigation">
        <article class="px-4 py-12 sm:px-6 sm:py-16">
            <nav
                v-if="props.product.categories.length > 0"
                class="flex flex-wrap gap-2 text-xs text-stone-500"
                :aria-label="t('catalog.categoriesAria')"
            >
                <Link
                    v-for="category in props.product.categories"
                    :key="category.slug"
                    :href="`/produtos/c/${category.slug}`"
                    class="hover:text-orange-700"
                >
                    {{ category.name }}
                </Link>
            </nav>

            <div class="mt-4 grid gap-8 lg:grid-cols-2">
                <div>
                    <img
                        v-if="props.product.images.length > 0"
                        :src="props.product.images[activeImage]"
                        :alt="props.product.name"
                        class="aspect-square w-full rounded-sm border border-stone-200 bg-white object-contain p-6 dark:border-stone-800"
                    />
                    <div
                        v-if="props.product.images.length > 1"
                        class="mt-2 flex flex-wrap gap-2"
                    >
                        <button
                            v-for="(image, index) in props.product.images"
                            :key="image"
                            type="button"
                            :class="[
                                'size-16 overflow-hidden rounded-sm border bg-white p-1',
                                index === activeImage
                                    ? 'border-orange-600'
                                    : 'border-stone-200 dark:border-stone-800',
                            ]"
                            :aria-label="`Ver foto ${index + 1}`"
                            @click="activeImage = index"
                        >
                            <img
                                :src="image"
                                alt=""
                                class="size-full object-contain"
                            />
                        </button>
                    </div>
                </div>

                <div>
                    <h1
                        class="font-display text-4xl tracking-tight text-stone-950 sm:text-5xl dark:text-stone-50"
                    >
                        {{ props.product.name }}
                    </h1>

                    <p
                        v-if="props.product.sku"
                        class="mt-2 text-sm text-stone-500"
                    >
                        {{ t('catalog.sku') }}: {{ props.product.sku }}
                    </p>

                    <div
                        v-if="props.product.description"
                        class="mt-6 text-sm leading-relaxed whitespace-pre-line text-stone-700 dark:text-stone-300"
                    >
                        {{ props.product.description }}
                    </div>

                    <div class="mt-8 flex flex-wrap items-center gap-3">
                        <button
                            type="button"
                            class="inline-flex h-10 items-center gap-2 rounded-sm bg-stone-900 px-5 text-sm font-medium text-stone-50 hover:bg-stone-800 dark:bg-stone-100 dark:text-stone-900 dark:hover:bg-stone-200"
                            @click="addProduct"
                        >
                            <Plus class="size-4" aria-hidden="true" />
                            {{ t('catalog.addToQuote') }}
                        </button>
                        <Link
                            href="/orcamento"
                            class="text-sm text-orange-700 hover:underline dark:text-orange-400"
                        >
                            {{ t('catalog.viewQuote') }}
                        </Link>
                        <span
                            v-if="added"
                            class="inline-flex items-center gap-1 text-sm text-emerald-600 dark:text-emerald-400"
                        >
                            <Check class="size-4" aria-hidden="true" />
                            {{ t('catalog.added') }}
                        </span>
                    </div>
                </div>
            </div>

            <section v-if="props.related.length > 0" class="mt-14">
                <h2
                    class="font-display text-2xl tracking-tight text-stone-950 dark:text-stone-50"
                >
                    {{ t('catalog.related') }}
                </h2>
                <ul class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <li
                        v-for="item in props.related"
                        :key="item.url"
                        class="rounded-sm border border-stone-200 p-4 dark:border-stone-800"
                    >
                        <Link
                            :href="item.url"
                            class="text-sm font-medium text-stone-900 hover:text-orange-700 dark:text-stone-100 dark:hover:text-orange-400"
                        >
                            {{ item.title }}
                        </Link>
                        <p
                            v-if="item.description"
                            class="mt-1 text-xs text-stone-500"
                        >
                            {{ item.description }}
                        </p>
                    </li>
                </ul>
            </section>
        </article>
    </SiteShell>
</template>
