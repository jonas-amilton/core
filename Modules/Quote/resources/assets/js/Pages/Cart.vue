<script setup lang="ts">
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { Check, Trash2 } from '@lucide/vue';
import { computed } from 'vue';
import SiteShell from '@modules/Page/resources/assets/js/components/SiteShell.vue';
import type { NavigationItem } from '@modules/Page/resources/assets/js/types';
import { destroy } from '@/routes/quote/item';
import { store } from '@/routes/quote';
import { formatPrice } from '@/lib/format';
import { siteFieldClasses } from '@/lib/formStyles';
import { useI18n } from '@/i18n';

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
    navigation: NavigationItem[];
}>();

const total = computed(() =>
    props.items.reduce(
        (sum, item) => sum + (Number(item.price) || 0) * item.quantity,
        0,
    ),
);

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
    <Head title="Orçamento" />

    <SiteShell :navigation="props.navigation">
        <section class="px-4 py-12 sm:px-6 sm:py-16">
            <h1
                class="text-3xl font-semibold tracking-tight text-slate-950 sm:text-4xl dark:text-slate-50"
            >
                {{ t('quote.title') }}
            </h1>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
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
                                class="text-sm font-medium text-slate-900 hover:text-blue-700 dark:text-slate-100 dark:hover:text-blue-400"
                            >
                                {{ item.name }}
                            </Link>
                            <p class="text-xs text-slate-500">
                                <template v-if="item.sku"
                                    >{{ t('catalog.sku') }} {{ item.sku }} ·
                                </template>
                                {{ t('quote.quantity') }} {{ item.quantity }}
                            </p>
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
                        class="flex items-center justify-between border-t border-slate-200 pt-3 dark:border-slate-800"
                    >
                        <span
                            class="text-sm font-medium text-slate-700 dark:text-slate-300"
                        >
                            {{ t('quote.total') }}
                        </span>
                        <span
                            class="text-xl font-semibold tracking-tight text-slate-950 tabular-nums dark:text-slate-50"
                        >
                            {{ formatPrice(total) }}
                        </span>
                    </div>

                    <button
                        type="submit"
                        class="inline-flex h-10 items-center justify-center rounded-md bg-blue-800 px-5 text-sm font-medium text-white hover:bg-blue-900 disabled:opacity-50"
                        :disabled="form.processing"
                    >
                        {{ t('quote.submit') }}
                    </button>
                </form>
            </div>

            <p
                v-else
                class="mt-8 rounded-lg border border-dashed border-slate-300 bg-white px-6 py-16 text-center text-sm text-slate-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-400"
            >
                {{ t('quote.empty') }}
                <Link
                    href="/produtos"
                    class="text-blue-700 hover:underline dark:text-blue-400"
                >
                    {{ t('quote.viewProducts') }}
                </Link>
            </p>
        </section>
    </SiteShell>
</template>
