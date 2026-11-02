<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import SiteShell from '@modules/Page/resources/assets/js/components/SiteShell.vue';
import type { NavigationItem } from '@modules/Page/resources/assets/js/types';
import { useI18n } from '@/i18n';

const props = defineProps<{
    cookieText: string;
    navigation: NavigationItem[];
}>();

const { locale, t } = useI18n();

const intro = (): string =>
    locale.value === 'pt_BR' && props.cookieText
        ? props.cookieText
        : t('cookiesPage.intro');

const essentials = [
    { name: 'session', key: 'session' },
    { name: 'XSRF-TOKEN', key: 'xsrf' },
    { name: 'corecms.cookie-consent', key: 'consent' },
];

const optional = [
    { name: 'google_analytics', key: 'analytics' },
    { name: 'google_tag_manager', key: 'tagManager' },
    { name: 'pixel_facebook', key: 'pixel' },
];
</script>

<template>
    <Head :title="t('cookiesPage.title')" />

    <SiteShell :navigation="navigation">
        <section class="px-4 py-12 sm:px-6 sm:py-16">
            <h1
                class="font-display text-4xl tracking-tight text-stone-950 sm:text-5xl dark:text-stone-50"
            >
                {{ t('cookiesPage.title') }}
            </h1>
            <p
                class="mt-2 max-w-2xl text-sm text-stone-600 dark:text-stone-400"
            >
                {{ intro() }}
            </p>

            <h2
                class="mt-12 font-display text-2xl tracking-tight text-stone-950 dark:text-stone-50"
            >
                {{ t('cookiesPage.essentialHeading') }}
            </h2>
            <p class="mt-1 text-sm text-stone-600 dark:text-stone-400">
                {{ t('cookiesPage.essentialIntro') }}
            </p>
            <div
                class="mt-4 overflow-hidden rounded-sm border border-stone-200 dark:border-stone-800"
            >
                <table class="w-full text-left text-sm">
                    <thead
                        class="border-b border-stone-200 bg-stone-50 text-xs text-stone-500 uppercase dark:border-stone-800 dark:bg-stone-950/60 dark:text-stone-400"
                    >
                        <tr>
                            <th scope="col" class="px-4 py-2.5 font-medium">
                                {{ t('cookiesPage.cookie') }}
                            </th>
                            <th scope="col" class="px-4 py-2.5 font-medium">
                                {{ t('cookiesPage.purpose') }}
                            </th>
                            <th scope="col" class="px-4 py-2.5 font-medium">
                                {{ t('cookiesPage.duration') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody
                        class="divide-y divide-stone-200 dark:divide-stone-800"
                    >
                        <tr v-for="cookie in essentials" :key="cookie.name">
                            <td class="px-4 py-3 font-mono text-xs">
                                {{ cookie.name }}
                            </td>
                            <td
                                class="px-4 py-3 text-stone-600 dark:text-stone-400"
                            >
                                {{
                                    t(`cookiesPage.items.${cookie.key}.purpose`)
                                }}
                            </td>
                            <td class="px-4 py-3 text-xs text-stone-500">
                                {{
                                    t(
                                        `cookiesPage.items.${cookie.key}.duration`,
                                    )
                                }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <h2
                class="mt-12 font-display text-2xl tracking-tight text-stone-950 dark:text-stone-50"
            >
                {{ t('cookiesPage.optionalHeading') }}
            </h2>
            <p class="mt-1 text-sm text-stone-600 dark:text-stone-400">
                {{ t('cookiesPage.optionalIntro') }}
            </p>
            <div
                class="mt-4 overflow-hidden rounded-sm border border-stone-200 dark:border-stone-800"
            >
                <table class="w-full text-left text-sm">
                    <thead
                        class="border-b border-stone-200 bg-stone-50 text-xs text-stone-500 uppercase dark:border-stone-800 dark:bg-stone-950/60 dark:text-stone-400"
                    >
                        <tr>
                            <th scope="col" class="px-4 py-2.5 font-medium">
                                {{ t('cookiesPage.cookie') }}
                            </th>
                            <th scope="col" class="px-4 py-2.5 font-medium">
                                {{ t('cookiesPage.purpose') }}
                            </th>
                            <th scope="col" class="px-4 py-2.5 font-medium">
                                {{ t('cookiesPage.duration') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody
                        class="divide-y divide-stone-200 dark:divide-stone-800"
                    >
                        <tr v-for="cookie in optional" :key="cookie.name">
                            <td class="px-4 py-3 font-mono text-xs">
                                {{ cookie.name }}
                            </td>
                            <td
                                class="px-4 py-3 text-stone-600 dark:text-stone-400"
                            >
                                {{
                                    t(`cookiesPage.items.${cookie.key}.purpose`)
                                }}
                            </td>
                            <td class="px-4 py-3 text-xs text-stone-500">
                                {{
                                    t(
                                        `cookiesPage.items.${cookie.key}.duration`,
                                    )
                                }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </SiteShell>
</template>
