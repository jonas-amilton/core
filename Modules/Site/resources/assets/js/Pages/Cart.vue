<script setup lang="ts">
import { Link, router, useForm } from '@inertiajs/vue3';
import { Check, Trash2 } from '@lucide/vue';
import { computed } from 'vue';
import SiteShell from '../components/SiteShell.vue';
import type { NavigationItem } from '@modules/Page/resources/assets/js/types';
import { destroy, update as updateItem } from '@/routes/quote/item';
import { store } from '@/routes/quote';
import { formatPrice } from '@/lib/format';
import { siteFieldClasses } from '@/lib/formStyles';
import { useI18n } from '@/i18n';
import SiteHead from '../components/SiteHead.vue';

type CartItem = {
    id: number;
    name: string;
    slug: string;
    sku: string | null;
    price: string | null;
    cover: string | null;
    quantity: number;
};

const { t } = useI18n();

const props = defineProps<{
    items: CartItem[];
    subtotal: number;
    discount: number;
    coupon: { code: string; percent: number } | null;
    navigation: NavigationItem[];
}>();

const total = computed(() => props.subtotal - props.discount);

const couponForm = useForm({ code: '' });
const couponError = computed(
    () =>
        (couponForm.errors as Record<string, string | undefined>).coupon,
);

function applyCoupon(): void {
    couponForm.post('/orcamento/cupom', {
        preserveScroll: true,
        onSuccess: () => couponForm.reset('code'),
    });
}

function removeCoupon(): void {
    router.delete('/orcamento/cupom', { preserveScroll: true });
}

const lineTotal = (item: CartItem): string | null =>
    formatPrice((Number(item.price) || 0) * item.quantity);

const cartError = computed(
    () => (form.errors as Record<string, string | undefined>).cart,
);

const form = useForm({
    name: '',
    email: '',
    phone: '',
    message: '',
    website: '',
});

function changeQuantity(item: CartItem, event: Event): void {
    const value = Number((event.target as HTMLInputElement).value);

    if (!Number.isFinite(value) || value < 1) {
        return;
    }

    router.patch(
        updateItem.url(item.id),
        { quantity: value },
        { preserveScroll: true },
    );
}

function remove(item: CartItem): void {
    router.delete(destroy.url(item.id), { preserveScroll: true });
}

function submit(): void {
    form.post(store.url(), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
}
</script>

<template>
    <SiteHead :title="t('quote.title')" />

    <SiteShell :navigation="props.navigation">
        <section class="px-4 py-12 sm:px-6 sm:py-16">
            <h1
                class="text-3xl font-semibold tracking-tight text-slate-950 sm:text-4xl dark:text-slate-50"
            >
                {{ t('quote.title') }}
            </h1>
            <p class="mt-1 text-sm text-slate-700 dark:text-slate-400">
                {{ t('quote.subtitle') }}
            </p>

            <p
                v-if="form.recentlySuccessful"
                class="mt-4 inline-flex items-center gap-1.5 rounded-sm bg-emerald-50 px-3 py-2 text-sm text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300"
            >
                <Check class="size-4" aria-hidden="true" />
                {{ t('quote.success') }}
            </p>

            <p
                v-if="cartError"
                class="mt-4 rounded-sm bg-red-50 px-3 py-2 text-sm text-red-700 dark:bg-red-500/10 dark:text-red-300"
            >
                {{ cartError }}
            </p>

            <div
                v-if="props.items.length > 0"
                class="mt-6 grid gap-6 lg:grid-cols-2"
            >
                <div class="grid content-start gap-3">
                    <ul class="grid content-start gap-2">
                    <li
                        v-for="item in props.items"
                        :key="item.id"
                        class="flex items-center gap-3 rounded-lg border border-slate-200 bg-white p-3 shadow-xs dark:border-slate-800 dark:bg-slate-900"
                    >
                        <img
                            v-if="item.cover"
                            :src="item.cover"
                            alt=""
                            class="size-16 rounded-md bg-white object-contain p-1"
                        />
                        <div class="min-w-0 flex-1">
                            <Link
                                :href="`/produto/${item.slug}`"
                                class="text-sm font-medium text-slate-900 hover:text-brand dark:text-slate-100 dark:hover:text-green-400"
                            >
                                {{ item.name }}
                            </Link>
                            <div
                                class="mt-1 flex items-center gap-3 text-xs text-slate-600"
                            >
                                <span v-if="item.sku">
                                    {{ t('catalog.sku') }} {{ item.sku }}
                                </span>
                                <label class="inline-flex items-center gap-1.5">
                                    <span class="sr-only">
                                        {{ t('quote.quantity') }}
                                    </span>
                                    <input
                                        type="number"
                                        min="1"
                                        max="999"
                                        :value="item.quantity"
                                        :aria-label="`${t('quote.quantity')} ${item.name}`"
                                        class="h-8 w-16 rounded-md border border-slate-300 bg-white px-2 text-sm text-slate-900 tabular-nums focus-visible:border-brand focus-visible:ring-2 focus-visible:ring-brand/20 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                                        @change="changeQuantity(item, $event)"
                                    />
                                </label>
                            </div>
                        </div>
                        <p
                            class="text-sm font-medium text-slate-950 tabular-nums dark:text-slate-50"
                        >
                            {{ lineTotal(item) ?? '—' }}
                        </p>
                        <button
                            type="button"
                            class="rounded-sm p-1 text-slate-400 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-500/10"
                            :aria-label="`${t('quote.remove')} ${item.name}`"
                            @click="remove(item)"
                        >
                            <Trash2 class="size-4" aria-hidden="true" />
                        </button>
                    </li>
                </ul>

                <div
                    class="rounded-lg border border-slate-200 bg-white p-4 shadow-xs dark:border-slate-800 dark:bg-slate-900"
                >
                    <div
                        v-if="props.coupon"
                        class="flex items-center justify-between gap-3 text-sm"
                    >
                        <span class="text-slate-700 dark:text-slate-300">
                            {{ t('quote.coupon') }}
                            <strong class="text-slate-950 dark:text-slate-50">
                                {{ props.coupon.code }}
                            </strong>
                            (−{{ props.coupon.percent }}%)
                        </span>
                        <button
                            type="button"
                            class="text-xs font-medium text-red-600 hover:underline"
                            @click="removeCoupon"
                        >
                            {{ t('quote.removeCoupon') }}
                        </button>
                    </div>

                    <form
                        v-else
                        class="flex gap-2"
                        @submit.prevent="applyCoupon"
                    >
                        <input
                            v-model="couponForm.code"
                            type="text"
                            :placeholder="t('quote.couponPlaceholder')"
                            :aria-label="t('quote.coupon')"
                            class="h-10 min-w-0 flex-1 rounded-md border border-slate-300 bg-white px-3 text-sm uppercase text-slate-900 placeholder:normal-case placeholder:text-slate-600 focus-visible:border-brand focus-visible:ring-2 focus-visible:ring-brand/20 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                        />
                        <button
                            type="submit"
                            class="inline-flex h-10 items-center rounded-md border border-slate-300 px-4 text-sm font-medium text-slate-700 hover:bg-white disabled:opacity-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800"
                            :disabled="couponForm.processing"
                        >
                            {{ t('quote.applyCoupon') }}
                        </button>
                    </form>

                    <p
                        v-if="couponError"
                        class="mt-2 text-xs text-red-600"
                    >
                        {{ couponError }}
                    </p>
                </div>
                </div>

                <form
                    class="grid content-start gap-3 rounded-lg border border-slate-200 bg-white p-4 shadow-xs dark:border-slate-800 dark:bg-slate-900"
                    @submit.prevent="submit"
                >
                    <label class="grid gap-1.5">
                        <span
                            class="text-sm font-medium text-slate-700 dark:text-slate-300"
                        >
                            {{ t('quote.name') }}
                        </span>
                        <input
                            v-model="form.name"
                            type="text"
                            :class="siteFieldClasses"
                        />
                        <span
                            v-if="form.errors.name"
                            class="text-xs text-red-600"
                        >
                            {{ form.errors.name }}
                        </span>
                    </label>

                    <label class="grid gap-1.5">
                        <span
                            class="text-sm font-medium text-slate-700 dark:text-slate-300"
                        >
                            {{ t('quote.email') }}
                        </span>
                        <input
                            v-model="form.email"
                            type="email"
                            :class="siteFieldClasses"
                        />
                        <span
                            v-if="form.errors.email"
                            class="text-xs text-red-600"
                        >
                            {{ form.errors.email }}
                        </span>
                    </label>

                    <label class="grid gap-1.5">
                        <span
                            class="text-sm font-medium text-slate-700 dark:text-slate-300"
                        >
                            {{ t('quote.phone') }}
                        </span>
                        <input
                            v-model="form.phone"
                            type="tel"
                            :class="siteFieldClasses"
                        />
                    </label>

                    <label class="grid gap-1.5">
                        <span
                            class="text-sm font-medium text-slate-700 dark:text-slate-300"
                        >
                            {{ t('quote.message') }}
                        </span>
                        <textarea
                            v-model="form.message"
                            :rows="4"
                            :class="[
                                siteFieldClasses,
                                'h-auto py-2 leading-relaxed',
                            ]"
                        />
                    </label>

                    <input
                        v-model="form.website"
                        type="text"
                        name="website"
                        tabindex="-1"
                        autocomplete="off"
                        class="hidden"
                        aria-hidden="true"
                    />

                    <div
                        class="grid gap-1 border-t border-slate-200 pt-3 text-sm dark:border-slate-800"
                    >
                        <p class="flex items-center justify-between">
                            <span class="text-slate-700 dark:text-slate-400">
                                {{ t('quote.subtotal') }}
                            </span>
                            <span class="text-slate-950 tabular-nums dark:text-slate-50">
                                {{ formatPrice(props.subtotal) }}
                            </span>
                        </p>
                        <p
                            v-if="props.discount > 0"
                            class="flex items-center justify-between text-emerald-700 dark:text-emerald-400"
                        >
                            <span>{{ t('quote.discount') }}</span>
                            <span class="tabular-nums">
                                −{{ formatPrice(props.discount) }}
                            </span>
                        </p>
                        <p class="mt-1 flex items-center justify-between">
                            <span
                                class="font-medium text-slate-700 dark:text-slate-300"
                            >
                                {{ t('quote.total') }}
                            </span>
                            <span
                                class="text-xl font-semibold tracking-tight text-slate-950 tabular-nums dark:text-slate-50"
                            >
                                {{ formatPrice(total) }}
                            </span>
                        </p>
                    </div>

                    <button
                        type="submit"
                        class="inline-flex h-10 items-center justify-center rounded-full bg-brand px-5 text-sm font-medium text-white hover:bg-brand-deep disabled:opacity-50"
                        :disabled="form.processing"
                    >
                        {{ t('quote.submit') }}
                    </button>
                </form>
            </div>

            <p
                v-else
                class="mt-8 rounded-lg border border-dashed border-slate-300 bg-white px-6 py-16 text-center text-sm text-slate-600 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-400"
            >
                {{ t('quote.empty') }}
                <Link
                    href="/produtos"
                    class="text-brand hover:underline dark:text-green-400"
                >
                    {{ t('quote.viewProducts') }}
                </Link>
            </p>
        </section>
    </SiteShell>
</template>
