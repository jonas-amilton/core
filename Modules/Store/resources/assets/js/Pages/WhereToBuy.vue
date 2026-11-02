<script setup lang="ts">

import SiteShell from '@modules/Page/resources/assets/js/components/SiteShell.vue';
import type { NavigationItem } from '@modules/Page/resources/assets/js/types';
import { useI18n } from '@/i18n';
import SiteHead from '@/components/SiteHead.vue';

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
    <SiteHead :title="t('stores.title')" />

    <SiteShell :navigation="props.navigation">
        <section class="px-4 py-12 sm:px-6 sm:py-16">
            <h1
                class="text-3xl font-semibold tracking-tight text-slate-950 sm:text-4xl dark:text-slate-50"
            >
                {{ t('stores.title') }}
            </h1>

            <h2
                v-if="props.physical.length > 0"
                class="mt-10 text-xl font-semibold tracking-tight text-slate-950 dark:text-slate-50"
            >
                {{ t('stores.physical') }}
            </h2>
            <ul class="mt-4 grid gap-3 sm:grid-cols-2">
                <li
                    v-for="store in props.physical"
                    :key="store.id"
                    class="rounded-lg border border-slate-200 bg-white p-4 shadow-xs dark:border-slate-800 dark:bg-slate-900"
                >
                    <p
                        class="text-sm font-medium text-slate-900 dark:text-slate-100"
                    >
                        {{ store.name }}
                    </p>
                    <p
                        v-if="store.address"
                        class="mt-1 text-sm text-slate-600 dark:text-slate-400"
                    >
                        {{ store.address }}
                    </p>
                    <a
                        v-if="store.phone"
                        :href="`tel:${store.phone}`"
                        class="mt-1 block text-sm text-blue-700 dark:text-blue-400"
                    >
                        {{ store.phone }}
                    </a>
                </li>
            </ul>

            <h2
                v-if="props.online.length > 0"
                class="mt-12 text-xl font-semibold tracking-tight text-slate-950 dark:text-slate-50"
            >
                {{ t('stores.online') }}
            </h2>
            <ul class="mt-4 grid gap-3 sm:grid-cols-2">
                <li
                    v-for="store in props.online"
                    :key="store.id"
                    class="rounded-lg border border-slate-200 bg-white p-4 shadow-xs dark:border-slate-800 dark:bg-slate-900"
                >
                    <a
                        v-if="store.url"
                        :href="store.url"
                        target="_blank"
                        rel="noopener"
                        class="text-sm font-medium text-blue-700 hover:underline dark:text-blue-400"
                    >
                        {{ store.name }}
                    </a>
                    <p
                        v-else
                        class="text-sm font-medium text-slate-900 dark:text-slate-100"
                    >
                        {{ store.name }}
                    </p>
                    <p
                        v-if="store.address"
                        class="mt-1 text-sm text-slate-600 dark:text-slate-400"
                    >
                        {{ store.address }}
                    </p>
                </li>
            </ul>

            <p
                v-if="props.physical.length === 0 && props.online.length === 0"
                class="mt-8 rounded-lg border border-dashed border-slate-300 bg-white px-6 py-16 text-center text-sm text-slate-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-400"
            >
                {{ t('stores.empty') }}
            </p>
        </section>
    </SiteShell>
</template>
