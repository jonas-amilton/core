<script setup lang="ts">
import { Link, router, useForm, usePage } from '@inertiajs/vue3';
import { Check, Heart, Plus } from '@lucide/vue';
import { computed, ref } from 'vue';
import { store as addToCart } from '@/routes/quote/item';
import { formatPrice } from '@/lib/format';
import { siteFieldClasses } from '@/lib/formStyles';
import { useI18n } from '@/i18n';
import SiteHead from '@/components/SiteHead.vue';
import SiteShell from '@modules/Page/resources/assets/js/components/SiteShell.vue';
import type { NavigationItem } from '@modules/Page/resources/assets/js/types';

type Product = {
    id: number;
    name: string;
    slug: string;
    sku: string | null;
    price: string | null;
    stock: number | null;
    description: string | null;
    images: string[];
    categories: { name: string; slug: string }[];
};

type Review = {
    id: number;
    name: string;
    rating: number;
    body: string;
    created_at: string | null;
};

type RelatedProduct = {
    title: string;
    description: string | null;
    price: string | null;
    url: string;
    cover: string | null;
};

const { t } = useI18n();

const props = defineProps<{
    product: Product;
    reviews: Review[];
    isFavorite: boolean;
    related: RelatedProduct[];
    navigation: NavigationItem[];
}>();

const reviewForm = useForm({
    name: '',
    email: '',
    rating: 5,
    body: '',
    website: '',
});

function submitReview(): void {
    reviewForm.post(`/produto/${props.product.slug}/avaliacao`, {
        preserveScroll: true,
        onSuccess: () => reviewForm.reset('body'),
    });
}

const activeImage = ref(0);
const added = ref(false);

const page = usePage();

const installmentLabel = computed<string | null>(() => {
    const site = (page.props.site as Record<string, string> | undefined) ?? {};
    const max = Number(site.max_installments ?? 0);
    const price = Number(props.product.price);

    if (!price || max < 2) {
        return null;
    }

    return t('catalog.installments')
        .replace(':count', String(max))
        .replace(':value', formatPrice(price / max) ?? '');
});

function toggleFavorite(): void {
    router.post(`/favoritos/${props.product.id}`, {}, { preserveScroll: true });
}

function addProduct(): void {
    router.post(
        addToCart.url(),
        { product_id: props.product.id, quantity: 1 },
        { preserveScroll: true, onSuccess: () => (added.value = true) },
    );
}
</script>

<template>
    <SiteHead
        :title="props.product.name"
        :description="props.product.description"
        :image="props.product.images[0] ?? null"
        type="product"
    />

    <SiteShell :navigation="props.navigation">
        <article class="px-4 py-12 sm:px-6 sm:py-16">
            <nav
                v-if="props.product.categories.length > 0"
                class="flex flex-wrap gap-2 text-xs text-slate-500"
                :aria-label="t('catalog.categoriesAria')"
            >
                <Link
                    v-for="category in props.product.categories"
                    :key="category.slug"
                    :href="`/produtos/c/${category.slug}`"
                    class="hover:text-blue-700"
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
                        class="aspect-square w-full rounded-lg border border-slate-200 bg-white object-contain p-6 dark:border-slate-800 dark:bg-slate-900"
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
                                    ? 'border-blue-600'
                                    : 'border-slate-200 dark:border-slate-800',
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
                        class="text-3xl font-semibold tracking-tight text-slate-950 sm:text-4xl dark:text-slate-50"
                    >
                        {{ props.product.name }}
                    </h1>

                    <p
                        v-if="props.product.sku"
                        class="mt-2 text-sm text-slate-500"
                    >
                        {{ t('catalog.sku') }}: {{ props.product.sku }}
                    </p>

                    <p
                        class="mt-3 text-3xl font-semibold tracking-tight text-slate-950 tabular-nums dark:text-slate-50"
                    >
                        {{
                            formatPrice(props.product.price) ??
                            t('catalog.priceOnRequest')
                        }}
                    </p>

                    <p
                        v-if="installmentLabel"
                        class="mt-1 text-sm text-slate-600 dark:text-slate-400"
                    >
                        {{ installmentLabel }}
                    </p>

                    <p
                        v-if="props.product.stock !== null"
                        class="mt-1 text-sm"
                        :class="
                            props.product.stock > 0
                                ? 'text-emerald-700 dark:text-emerald-400'
                                : 'text-red-600 dark:text-red-400'
                        "
                    >
                        {{
                            props.product.stock > 0
                                ? t('catalog.inStock').replace(
                                      ':count',
                                      String(props.product.stock),
                                  )
                                : t('catalog.outOfStock')
                        }}
                    </p>

                    <div
                        v-if="props.product.description"
                        class="mt-6 text-[15px] leading-[1.75] whitespace-pre-line text-slate-700 dark:text-slate-300"
                    >
                        {{ props.product.description }}
                    </div>

                    <div class="mt-8 flex flex-wrap items-center gap-3">
                        <button
                            v-if="props.product.stock !== 0"
                            type="button"
                            class="inline-flex h-10 items-center gap-2 rounded-md bg-blue-800 px-5 text-sm font-medium text-white hover:bg-blue-900"
                            @click="addProduct"
                        >
                            <Plus class="size-4" aria-hidden="true" />
                            {{ t('catalog.addToCart') }}
                        </button>
                        <button
                            type="button"
                            class="inline-flex h-10 items-center gap-2 rounded-md border border-slate-300 px-4 text-sm font-medium text-slate-700 transition-colors hover:border-red-300 hover:text-red-600 dark:border-slate-700 dark:text-slate-300"
                            @click="toggleFavorite"
                        >
                            <Heart
                                class="size-4"
                                :class="
                                    props.isFavorite
                                        ? 'fill-red-500 text-red-500'
                                        : ''
                                "
                                aria-hidden="true"
                            />
                            {{
                                props.isFavorite
                                    ? t('catalog.favorited')
                                    : t('catalog.favorite')
                            }}
                        </button>
                        <Link
                            href="/orcamento"
                            class="text-sm text-blue-700 hover:underline dark:text-blue-400"
                        >
                            {{ t('catalog.viewCart') }}
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

            <section class="mt-14">
                <h2
                    class="text-xl font-semibold tracking-tight text-slate-950 dark:text-slate-50"
                >
                    {{ t('reviews.title') }} ({{ props.reviews.length }})
                </h2>

                <p
                    v-if="props.reviews.length === 0"
                    class="mt-3 text-sm text-slate-500 dark:text-slate-400"
                >
                    {{ t('reviews.empty') }}
                </p>

                <ul v-else class="mt-4 grid gap-4">
                    <li
                        v-for="review in props.reviews"
                        :key="review.id"
                        class="rounded-lg border border-slate-200 bg-white p-4 shadow-xs dark:border-slate-800 dark:bg-slate-900"
                    >
                        <p
                            class="text-sm font-semibold text-slate-950 dark:text-slate-50"
                        >
                            {{ review.name }}
                            <span
                                class="ml-1 text-amber-500"
                                :aria-label="`${review.rating} / 5`"
                            >
                                {{ '★'.repeat(review.rating) }}
                            </span>
                        </p>
                        <p
                            class="mt-2 text-sm leading-relaxed whitespace-pre-line text-slate-600 dark:text-slate-400"
                        >
                            {{ review.body }}
                        </p>
                    </li>
                </ul>

                <div
                    class="mt-6 max-w-xl rounded-lg border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900"
                >
                    <h3
                        class="text-base font-semibold text-slate-950 dark:text-slate-50"
                    >
                        {{ t('reviews.formTitle') }}
                    </h3>

                    <p
                        v-if="reviewForm.recentlySuccessful"
                        class="mt-2 text-sm text-emerald-700 dark:text-emerald-400"
                    >
                        {{ t('reviews.success') }}
                    </p>

                    <form
                        class="mt-4 grid gap-3 sm:grid-cols-2"
                        @submit.prevent="submitReview"
                    >
                        <label class="grid gap-1.5">
                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                {{ t('reviews.name') }}
                            </span>
                            <input
                                v-model="reviewForm.name"
                                type="text"
                                :class="[
                                    siteFieldClasses,
                                    'h-10',
                                ]"
                            />
                            <span
                                v-if="reviewForm.errors.name"
                                class="text-xs text-red-600"
                            >
                                {{ reviewForm.errors.name }}
                            </span>
                        </label>

                        <label class="grid gap-1.5">
                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                {{ t('reviews.email') }}
                            </span>
                            <input
                                v-model="reviewForm.email"
                                type="email"
                                :class="[
                                    siteFieldClasses,
                                    'h-10',
                                ]"
                            />
                            <span
                                v-if="reviewForm.errors.email"
                                class="text-xs text-red-600"
                            >
                                {{ reviewForm.errors.email }}
                            </span>
                        </label>

                        <label class="grid gap-1.5">
                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                {{ t('reviews.rating') }}
                            </span>
                            <select
                                v-model.number="reviewForm.rating"
                                :class="[siteFieldClasses, 'h-10']"
                            >
                                <option :value="5">5 ★</option>
                                <option :value="4">4 ★</option>
                                <option :value="3">3 ★</option>
                                <option :value="2">2 ★</option>
                                <option :value="1">1 ★</option>
                            </select>
                        </label>

                        <label class="grid gap-1.5 sm:col-span-2">
                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                {{ t('reviews.body') }}
                            </span>
                            <textarea
                                v-model="reviewForm.body"
                                :rows="4"
                                :class="[
                                    siteFieldClasses,
                                    'py-2 leading-relaxed',
                                ]"
                            />
                            <span
                                v-if="reviewForm.errors.body"
                                class="text-xs text-red-600"
                            >
                                {{ reviewForm.errors.body }}
                            </span>
                        </label>

                        <input
                            v-model="reviewForm.website"
                            type="text"
                            name="website"
                            tabindex="-1"
                            autocomplete="off"
                            class="hidden"
                            aria-hidden="true"
                        />

                        <div class="sm:col-span-2">
                            <button
                                type="submit"
                                class="inline-flex h-10 items-center justify-center rounded-md bg-blue-800 px-5 text-sm font-medium text-white hover:bg-blue-900 disabled:opacity-50"
                                :disabled="reviewForm.processing"
                            >
                                {{ t('reviews.submit') }}
                            </button>
                        </div>
                    </form>
                </div>
            </section>

            <section v-if="props.related.length > 0" class="mt-14">
                <h2
                    class="text-xl font-semibold tracking-tight text-slate-950 dark:text-slate-50"
                >
                    {{ t('catalog.related') }}
                </h2>
                <ul class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <li
                        v-for="item in props.related"
                        :key="item.url"
                        class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-xs transition-colors hover:border-blue-300 dark:border-slate-800 dark:bg-slate-900 dark:hover:border-blue-500/40"
                    >
                        <Link :href="item.url" class="block">
                            <img
                                v-if="item.cover"
                                :src="item.cover"
                                alt=""
                                class="aspect-square w-full bg-white object-contain p-4"
                            />
                        </Link>
                        <div class="border-t border-slate-100 p-4 dark:border-slate-800">
                            <Link
                                :href="item.url"
                                class="text-sm font-medium text-slate-900 hover:text-blue-700 dark:text-slate-100 dark:hover:text-blue-400"
                            >
                                {{ item.title }}
                            </Link>
                            <p
                                v-if="item.description"
                                class="mt-1 text-xs text-slate-500"
                            >
                                {{ item.description }}
                            </p>
                            <p
                                class="mt-2 text-base font-semibold text-slate-950 tabular-nums dark:text-slate-50"
                            >
                                {{
                                    formatPrice(item.price) ??
                                    t('catalog.priceOnRequest')
                                }}
                            </p>
                        </div>
                    </li>
                </ul>
            </section>
        </article>
    </SiteShell>
</template>
