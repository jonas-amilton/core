<script setup lang="ts">
import { Link, router, usePage } from '@inertiajs/vue3';
import SiteHead from '@/components/SiteHead.vue';
import SiteShell from '@modules/Page/resources/assets/js/components/SiteShell.vue';
import type { NavigationItem } from '@modules/Page/resources/assets/js/types';
import { formatPrice } from '@/lib/format';
import { useI18n } from '@/i18n';

type OrderItem = {
    name: string;
    quantity: number;
    unit_price: string | null;
};

type Favorite = {
    id: number;
    name: string;
    slug: string;
    price: string | null;
    cover: string | null;
};

type Order = {
    id: number;
    created_at: string | null;
    answered: boolean;
    items: OrderItem[];
    total: number;
};

const { locale, t } = useI18n();

const props = defineProps<{
    orders: Order[];
    favorites: Favorite[];
    navigation: NavigationItem[];
}>();

const page = usePage();
const user = (page.props.auth as { user?: { name: string } } | undefined)?.user;

const formatDate = (value: string | null): string =>
    value
        ? new Date(value).toLocaleDateString(locale.value.replace('_', '-'), {
              day: '2-digit',
              month: 'long',
              year: 'numeric',
          })
        : '';
</script>

<template>
    <SiteHead :title="t('account.title')" />

    <SiteShell :navigation="props.navigation">
        <section class="px-4 py-12 sm:px-6 sm:py-16">
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <h1
                        class="text-3xl font-semibold tracking-tight text-slate-950 sm:text-4xl dark:text-slate-50"
                    >
                        {{ t('account.title') }}
                    </h1>
                    <p class="mt-1 text-sm text-slate-700 dark:text-slate-400">
                        {{ t('account.greeting') }},
                        {{ user?.name ?? '' }}
                    </p>
                </div>

                <button
                    type="button"
                    class="inline-flex h-9 items-center rounded-md border border-slate-300 px-4 text-sm font-medium text-slate-700 hover:bg-white dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"
                    @click="router.post('/logout')"
                >
                    {{ t('account.logout') }}
                </button>
            </div>

            <h2
                class="mt-10 text-xl font-semibold tracking-tight text-slate-950 dark:text-slate-50"
            >
                {{ t('account.orders') }}
            </h2>

            <ul v-if="props.orders.length > 0" class="mt-4 grid gap-4">
                <li
                    v-for="order in props.orders"
                    :key="order.id"
                    class="rounded-lg border border-slate-200 bg-white p-5 shadow-xs dark:border-slate-800 dark:bg-slate-900"
                >
                    <div
                        class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-100 pb-3 dark:border-slate-800"
                    >
                        <p class="text-sm font-semibold text-slate-950 dark:text-slate-50">
                            {{ t('account.order') }} #{{ order.id }}
                            <span class="font-normal text-slate-600">
                                · {{ formatDate(order.created_at) }}
                            </span>
                        </p>
                        <span
                            class="rounded-full border px-2.5 py-0.5 text-xs font-medium"
                            :class="
                                order.answered
                                    ? 'border-emerald-200 bg-emerald-50 text-emerald-800 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-300'
                                    : 'border-slate-200 bg-white text-slate-700 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300'
                            "
                        >
                            {{
                                order.answered
                                    ? t('account.statusAnswered')
                                    : t('account.statusOpen')
                            }}
                        </span>
                    </div>

                    <ul class="mt-3 grid gap-1.5 text-sm">
                        <li
                            v-for="(item, index) in order.items"
                            :key="index"
                            class="flex items-center justify-between gap-4"
                        >
                            <span class="text-slate-700 dark:text-slate-300">
                                {{ item.quantity }}× {{ item.name }}
                            </span>
                            <span
                                class="text-slate-950 tabular-nums dark:text-slate-50"
                            >
                                {{
                                    formatPrice(
                                        (Number(item.unit_price) || 0) *
                                            item.quantity,
                                    ) ?? t('catalog.priceOnRequest')
                                }}
                            </span>
                        </li>
                    </ul>

                    <p
                        class="mt-3 flex items-center justify-between border-t border-slate-100 pt-3 text-sm dark:border-slate-800"
                    >
                        <span class="font-medium text-slate-700 dark:text-slate-300">
                            {{ t('quote.total') }}
                        </span>
                        <span
                            class="text-lg font-semibold text-slate-950 tabular-nums dark:text-slate-50"
                        >
                            {{ formatPrice(order.total) }}
                        </span>
                    </p>
                </li>
            </ul>

            <p
                v-else
                class="mt-4 rounded-lg border border-dashed border-slate-300 bg-white px-6 py-16 text-center text-sm text-slate-600 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-400"
            >
                {{ t('account.empty') }}
                <Link href="/produtos" class="text-brand hover:underline dark:text-green-400">
                    {{ t('quote.viewProducts') }}
                </Link>
            </p>

            <h2
                class="mt-12 text-xl font-semibold tracking-tight text-slate-950 dark:text-slate-50"
            >
                {{ t('account.favorites') }}
            </h2>

            <ul
                v-if="props.favorites.length > 0"
                class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3"
            >
                <li
                    v-for="favorite in props.favorites"
                    :key="favorite.id"
                    class="rounded-lg border border-slate-200 bg-white p-4 shadow-xs dark:border-slate-800 dark:bg-slate-900"
                >
                    <Link
                        :href="`/produto/${favorite.slug}`"
                        class="flex items-center gap-3"
                    >
                        <img
                            v-if="favorite.cover"
                            :src="favorite.cover"
                            alt=""
                            class="size-16 shrink-0 rounded-md bg-white object-contain p-1"
                        />
                        <span class="min-w-0">
                            <span
                                class="block text-sm font-medium text-slate-900 hover:text-brand dark:text-slate-100"
                            >
                                {{ favorite.name }}
                            </span>
                            <span
                                class="mt-1 block text-sm font-semibold text-slate-950 tabular-nums dark:text-slate-50"
                            >
                                {{
                                    formatPrice(favorite.price) ??
                                    t('catalog.priceOnRequest')
                                }}
                            </span>
                        </span>
                    </Link>
                </li>
            </ul>

            <p
                v-else
                class="mt-4 text-sm text-slate-600 dark:text-slate-400"
            >
                {{ t('account.emptyFavorites') }}
            </p>
        </section>
    </SiteShell>
</template>
