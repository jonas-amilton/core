<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import SiteShell from '@modules/Page/resources/assets/js/components/SiteShell.vue';
import type { NavigationItem } from '@modules/Page/resources/assets/js/types';

type StoreItem = {
    id: number;
    name: string;
    kind_label: string;
    address: string | null;
    phone: string | null;
    url: string | null;
};

const props = defineProps<{
    physical: StoreItem[];
    online: StoreItem[];
    navigation: NavigationItem[];
}>();
</script>

<template>
    <Head title="Onde comprar" />

    <SiteShell :navigation="props.navigation">
        <section class="px-4 py-10 sm:px-6">
            <h1
                class="text-3xl font-semibold tracking-tight text-slate-900 dark:text-slate-100"
            >
                Onde comprar
            </h1>

            <h2
                v-if="props.physical.length > 0"
                class="mt-8 text-lg font-semibold text-slate-900 dark:text-slate-100"
            >
                Lojas físicas
            </h2>
            <ul class="mt-4 grid gap-3 sm:grid-cols-2">
                <li
                    v-for="store in props.physical"
                    :key="store.id"
                    class="rounded-xl border border-slate-200 p-4 dark:border-slate-800"
                >
                    <p class="text-sm font-medium text-slate-900 dark:text-slate-100">
                        {{ store.name }}
                    </p>
                    <p v-if="store.address" class="mt-1 text-sm text-slate-600 dark:text-slate-400">
                        {{ store.address }}
                    </p>
                    <a
                        v-if="store.phone"
                        :href="`tel:${store.phone}`"
                        class="mt-1 block text-sm text-indigo-600 dark:text-indigo-400"
                    >
                        {{ store.phone }}
                    </a>
                </li>
            </ul>

            <h2
                v-if="props.online.length > 0"
                class="mt-10 text-lg font-semibold text-slate-900 dark:text-slate-100"
            >
                Lojas virtuais
            </h2>
            <ul class="mt-4 grid gap-3 sm:grid-cols-2">
                <li
                    v-for="store in props.online"
                    :key="store.id"
                    class="rounded-xl border border-slate-200 p-4 dark:border-slate-800"
                >
                    <a
                        v-if="store.url"
                        :href="store.url"
                        target="_blank"
                        rel="noopener"
                        class="text-sm font-medium text-indigo-600 hover:underline dark:text-indigo-400"
                    >
                        {{ store.name }}
                    </a>
                    <p v-else class="text-sm font-medium text-slate-900 dark:text-slate-100">
                        {{ store.name }}
                    </p>
                    <p v-if="store.address" class="mt-1 text-sm text-slate-600 dark:text-slate-400">
                        {{ store.address }}
                    </p>
                </li>
            </ul>

            <p
                v-if="props.physical.length === 0 && props.online.length === 0"
                class="mt-8 rounded-xl border border-dashed border-slate-300 px-6 py-16 text-center text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400"
            >
                Nenhuma loja cadastrada ainda.
            </p>
        </section>
    </SiteShell>
</template>
