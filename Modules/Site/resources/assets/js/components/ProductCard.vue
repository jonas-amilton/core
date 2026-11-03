<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { Heart } from '@lucide/vue';
import { ref } from 'vue';
import { store as addToCart } from '@/routes/quote/item';
import { formatPrice } from '@/lib/format';
import { useI18n } from '@/i18n';

export type CardProduct = {
    id: number;
    name: string;
    slug: string;
    sku?: string | null;
    price: string | null;
    stock?: number | null;
    rating?: number | null;
    review_count?: number;
    cover: string | null;
};

const { t } = useI18n();

const props = defineProps<{
    product: CardProduct;
}>();

const added = ref(false);

function add(): void {
    router.post(
        addToCart.url(),
        { product_id: props.product.id, quantity: 1 },
        { preserveScroll: true, onSuccess: () => (added.value = true) },
    );
}

function favorite(): void {
    router.post(`/favoritos/${props.product.id}`, {}, { preserveScroll: true });
}
</script>

<template>
    <article class="group flex h-full flex-col">
        <div
            class="relative aspect-square overflow-hidden rounded-xl bg-[#f5f5f5] dark:bg-[#ececec]"
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
                class="absolute top-3 right-3 flex size-9 items-center justify-center rounded-full bg-white text-slate-600 shadow-sm transition-colors hover:text-red-500"
                :aria-label="`${t('catalog.favorite')}: ${product.name}`"
                @click="favorite"
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
                {{ formatPrice(product.price) ?? t('catalog.priceOnRequest') }}
            </p>
        </div>

        <div class="flex-1">
            <p
                v-if="product.sku"
                class="mt-1 text-xs text-slate-600 dark:text-slate-400"
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
                <span class="ml-1 text-slate-600 dark:text-slate-400">
                    ({{ product.review_count ?? 0 }})
                </span>
            </p>
        </div>

        <button
            v-if="product.stock !== 0"
            type="button"
            :class="[
                'mt-4 inline-flex h-10 w-full items-center justify-center gap-2 rounded-full border text-sm font-medium transition-colors',
                added
                    ? 'border-brand bg-brand text-white'
                    : 'border-brand/30 text-brand-deep hover:border-brand hover:bg-brand hover:text-white dark:border-slate-700 dark:text-slate-200',
            ]"
            :aria-label="`${t('catalog.addToCart')}: ${product.name}`"
            @click="add"
        >
            {{ added ? t('catalog.added') : t('catalog.addToCart') }}
        </button>
        <p
            v-else
            class="mt-4 inline-flex h-10 items-center justify-center rounded-full border border-slate-200 text-sm font-medium text-slate-600 dark:border-slate-700 dark:text-slate-400"
        >
            {{ t('catalog.outOfStock') }}
        </p>
    </article>
</template>
