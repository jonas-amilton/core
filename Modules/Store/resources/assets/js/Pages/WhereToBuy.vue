<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import SiteShell from '@modules/Page/resources/assets/js/components/SiteShell.vue';
import type { NavigationItem } from '@modules/Page/resources/assets/js/types';
import { useI18n } from '@/i18n';

type StoreItem = {
    id: number;
    name: string;
    kind_label: string;
    address: string | null;
    phone: string | null;
    url: string | null;
};

const { t } = useI18n();

const props = defineProps<{
    physical: StoreItem[];
    online: StoreItem[];
    navigation: NavigationItem[];
}>();
</script>

<template>
    <Head :title="t('stores.title')" />

    <SiteShell :navigation="props.navigation">
        <section class="px-4 py-12 sm:px-6 sm:py-16">
            <h1
                class="font-display text-4xl tracking-tight text-stone-950 sm:text-5xl dark:text-stone-50"
            >
                {{ t('stores.title') }}
            </h1>

            <h2
                v-if="props.physical.length > 0"
                class="mt-10 font-display text-2xl tracking-tight text-stone-950 dark:text-stone-50"
            >
                {{ t('stores.physical') }}
            </h2>
            <ul class="mt-4 grid gap-3 sm:grid-cols-2">
                <li
                    v-for="store in props.physical"
                    :key="store.id"
                    class="rounded-sm border border-stone-200 p-4 dark:border-stone-800"
                >
                    <p
                        class="text-sm font-medium text-stone-900 dark:text-stone-100"
                    >
                        {{ store.name }}
                    </p>
                    <p
                        v-if="store.address"
                        class="mt-1 text-sm text-stone-600 dark:text-stone-400"
                    >
                        {{ store.address }}
                    </p>
                    <a
                        v-if="store.phone"
                        :href="`tel:${store.phone}`"
                        class="mt-1 block text-sm text-orange-700 dark:text-orange-400"
                    >
                        {{ store.phone }}
                    </a>
                </li>
            </ul>

            <h2
                v-if="props.online.length > 0"
                class="mt-12 font-display text-2xl tracking-tight text-stone-950 dark:text-stone-50"
            >
                {{ t('stores.online') }}
            </h2>
            <ul class="mt-4 grid gap-3 sm:grid-cols-2">
                <li
                    v-for="store in props.online"
                    :key="store.id"
                    class="rounded-sm border border-stone-200 p-4 dark:border-stone-800"
                >
                    <a
                        v-if="store.url"
                        :href="store.url"
                        target="_blank"
                        rel="noopener"
                        class="text-sm font-medium text-orange-700 hover:underline dark:text-orange-400"
                    >
                        {{ store.name }}
                    </a>
                    <p
                        v-else
                        class="text-sm font-medium text-stone-900 dark:text-stone-100"
                    >
                        {{ store.name }}
                    </p>
                    <p
                        v-if="store.address"
                        class="mt-1 text-sm text-stone-600 dark:text-stone-400"
                    >
                        {{ store.address }}
                    </p>
                </li>
            </ul>

            <p
                v-if="props.physical.length === 0 && props.online.length === 0"
                class="mt-8 rounded-sm border border-dashed border-stone-300 px-6 py-16 text-center text-sm text-stone-500 dark:border-stone-700 dark:text-stone-400"
            >
                {{ t('stores.empty') }}
            </p>
        </section>
    </SiteShell>
</template>
