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
        <article class="px-4 py-10 sm:px-6">
            <nav
                v-if="props.product.categories.length > 0"
                class="flex flex-wrap gap-2 text-xs text-slate-500"
                aria-label="Categorias do produto"
            >
                <Link
                    v-for="category in props.product.categories"
                    :key="category.slug"
                    :href="`/produtos/c/${category.slug}`"
                    class="hover:text-indigo-600"
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
                        class="aspect-square w-full rounded-xl border border-slate-200 bg-white object-contain p-6 dark:border-slate-800"
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
                                'size-16 overflow-hidden rounded-lg border bg-white p-1',
                                index === activeImage
                                    ? 'border-indigo-500'
                                    : 'border-slate-200 dark:border-slate-800',
                            ]"
                            :aria-label="`Ver foto ${index + 1}`"
                            @click="activeImage = index"
                        >
                            <img :src="image" alt="" class="size-full object-contain" />
                        </button>
                    </div>
                </div>

                <div>
                    <h1
                        class="text-3xl font-semibold tracking-tight text-slate-900 dark:text-slate-100"
                    >
                        {{ props.product.name }}
                    </h1>

                    <p v-if="props.product.sku" class="mt-2 text-sm text-slate-500">
                        {{ t('catalog.sku') }}: {{ props.product.sku }}
                    </p>

                    <div
                        v-if="props.product.description"
                        class="mt-6 whitespace-pre-line text-sm leading-relaxed text-slate-700 dark:text-slate-300"
                    >
                        {{ props.product.description }}
                    </div>

                    <div class="mt-8 flex flex-wrap items-center gap-3">
                        <button
                            type="button"
                            class="inline-flex h-10 items-center gap-2 rounded-lg bg-indigo-600 px-5 text-sm font-medium text-white hover:bg-indigo-500"
                            @click="addProduct"
                        >
                            <Plus class="size-4" aria-hidden="true" />
                            {{ t('catalog.addToQuote') }}
                        </button>
                        <Link
                            href="/orcamento"
                            class="text-sm text-indigo-600 hover:underline dark:text-indigo-400"
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
                    class="text-lg font-semibold tracking-tight text-slate-900 dark:text-slate-100"
                >
                    {{ t('catalog.related') }}
                </h2>
                <ul class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <li
                        v-for="item in props.related"
                        :key="item.url"
                        class="rounded-xl border border-slate-200 p-4 dark:border-slate-800"
                    >
                        <Link
                            :href="item.url"
                            class="text-sm font-medium text-slate-900 hover:text-indigo-600 dark:text-slate-100 dark:hover:text-indigo-400"
                        >
                            {{ item.title }}
                        </Link>
                        <p v-if="item.description" class="mt-1 text-xs text-slate-500">
                            {{ item.description }}
                        </p>
                    </li>
                </ul>
            </section>
        </article>
    </SiteShell>
</template>
