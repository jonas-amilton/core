<script setup lang="ts">
import { Link, router, useForm, usePage } from '@inertiajs/vue3';
import { Check, Heart, Plus } from '@lucide/vue';
import { computed, ref } from 'vue';
import { store as addToCart } from '@/routes/quote/item';
import ProductCard from '../components/ProductCard.vue';
import type { CardProduct } from '../components/ProductCard.vue';
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

const { t } = useI18n();

const props = defineProps<{
    product: Product;
    reviews: Review[];
    isFavorite: boolean;
    related: CardProduct[];
    recentlyViewed: CardProduct[];
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
const quantity = ref(1);

const averageRating = computed<number | null>(() => {
    if (props.reviews.length === 0) {
        return null;
    }

    const total = props.reviews.reduce((sum, review) => sum + review.rating, 0);

    return Math.round((total / props.reviews.length) * 10) / 10;
});

const maxQuantity = computed(() =>
    props.product.stock === null ? 999 : Math.max(1, props.product.stock),
);

function stepQuantity(delta: number): void {
    quantity.value = Math.min(
        maxQuantity.value,
        Math.max(1, quantity.value + delta),
    );
}

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
        { product_id: props.product.id, quantity: quantity.value },
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
                class="flex flex-wrap gap-2 text-xs text-slate-600"
                :aria-label="t('catalog.categoriesAria')"
            >
                <Link
                    v-for="category in props.product.categories"
                    :key="category.slug"
                    :href="`/produtos/c/${category.slug}`"
                    class="hover:text-brand"
                >
                    {{ category.name }}
                </Link>
            </nav>

            <div class="mt-4 grid gap-8 lg:grid-cols-2">
                <div>
                    <div
                        v-if="props.product.images.length > 0"
                        class="aspect-square w-full overflow-hidden rounded-xl bg-[#f5f5f5] p-8 dark:bg-[#ececec]"
                    >
                        <img
                            :src="props.product.images[activeImage]"
                            :alt="props.product.name"
                            class="h-full w-full object-contain"
                        />
                    </div>
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
                                    ? 'border-green-700'
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
                    <p
                        v-if="averageRating"
                        class="flex items-center gap-2 text-sm text-brand dark:text-green-400"
                        :aria-label="`${averageRating} / 5`"
                    >
                        <span aria-hidden="true">
                            {{ '★'.repeat(Math.round(averageRating)) }}
                        </span>
                        <span class="text-slate-600 dark:text-slate-400">
                            ({{ props.reviews.length }})
                        </span>
                    </p>

                    <h1
                        class="mt-1 text-3xl font-semibold tracking-tight text-slate-950 sm:text-4xl dark:text-slate-50"
                    >
                        {{ props.product.name }}
                    </h1>

                    <p
                        v-if="props.product.sku"
                        class="mt-2 text-sm text-slate-600"
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
                        class="mt-1 text-sm text-slate-700 dark:text-slate-400"
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

                    <div class="mt-8 flex flex-wrap items-center gap-2">
                        <div
                            v-if="props.product.stock !== 0"
                            class="inline-flex h-11 items-center rounded-full border border-slate-300 dark:border-slate-700"
                        >
                            <button
                                type="button"
                                class="flex size-11 items-center justify-center text-lg text-slate-700 hover:text-brand dark:text-slate-300"
                                aria-label="-"
                                @click="stepQuantity(-1)"
                            >
                                −
                            </button>
                            <input
                                v-model.number="quantity"
                                type="number"
                                min="1"
                                :max="maxQuantity"
                                class="h-11 w-12 border-0 bg-transparent text-center text-sm tabular-nums focus:outline-none"
                                :aria-label="t('quote.quantity')"
                            />
                            <button
                                type="button"
                                class="flex size-11 items-center justify-center text-lg text-slate-700 hover:text-brand dark:text-slate-300"
                                aria-label="+"
                                @click="stepQuantity(1)"
                            >
                                +
                            </button>
                        </div>

                        <button
                            v-if="props.product.stock !== 0"
                            type="button"
                            class="inline-flex h-11 items-center gap-2 rounded-full bg-brand px-6 text-sm font-medium text-white transition-colors hover:bg-brand-deep"
                            @click="addProduct"
                        >
                            <Plus class="size-4" aria-hidden="true" />
                            {{ t('catalog.addToCart') }}
                        </button>

                        <button
                            type="button"
                            class="inline-flex size-11 items-center justify-center rounded-full border border-slate-300 text-slate-700 transition-colors hover:border-red-300 hover:text-red-600 dark:border-slate-700 dark:text-slate-300"
                            :aria-label="
                                props.isFavorite
                                    ? t('catalog.favorited')
                                    : t('catalog.favorite')
                            "
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
                        </button>

                        <Link
                            href="/orcamento"
                            class="text-sm text-brand hover:underline dark:text-green-400"
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

                    <ul
                        class="mt-8 divide-y divide-slate-200 overflow-hidden rounded-xl border border-slate-200 text-sm text-slate-700 dark:divide-slate-800 dark:border-slate-800 dark:text-slate-300"
                    >
                        <li
                            v-for="benefit in [
                                t('hero.benefit3'),
                                t('hero.benefit2'),
                                t('hero.benefit1'),
                            ]"
                            :key="benefit"
                            class="flex items-center gap-3 px-4 py-3"
                        >
                            <Check
                                class="size-4 shrink-0 text-brand dark:text-green-400"
                                aria-hidden="true"
                            />
                            {{ benefit }}
                        </li>
                    </ul>
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
                    class="mt-3 text-sm text-slate-600 dark:text-slate-400"
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
                            class="mt-2 text-sm leading-relaxed whitespace-pre-line text-slate-700 dark:text-slate-400"
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
                                class="inline-flex h-10 items-center justify-center rounded-full bg-brand px-5 text-sm font-medium text-white hover:bg-brand-deep disabled:opacity-50"
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
                <ul
                    class="-mx-4 mt-4 flex snap-x gap-4 overflow-x-auto px-4 pb-2 sm:mx-0 sm:px-0"
                >
                    <li
                        v-for="item in props.related"
                        :key="item.id"
                        class="w-52 shrink-0 snap-start"
                    >
                        <ProductCard :product="item" />
                    </li>
                </ul>
            </section>

            <section v-if="props.recentlyViewed.length > 0" class="mt-12">
                <h2
                    class="text-xl font-semibold tracking-tight text-slate-950 dark:text-slate-50"
                >
                    {{ t('catalog.recentlyViewed') }}
                </h2>
                <ul
                    class="-mx-4 mt-4 flex snap-x gap-4 overflow-x-auto px-4 pb-2 sm:mx-0 sm:px-0"
                >
                    <li
                        v-for="item in props.recentlyViewed"
                        :key="item.id"
                        class="w-52 shrink-0 snap-start"
                    >
                        <ProductCard :product="item" />
                    </li>
                </ul>
            </section>
        </article>
    </SiteShell>
</template>
