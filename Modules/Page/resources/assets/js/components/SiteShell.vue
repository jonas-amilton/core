<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { Clock, Mail, Menu, Phone, ShoppingBag } from '@lucide/vue';
import { computed } from 'vue';
import CookieConsent from '@/components/CookieConsent.vue';
import { switchLocale, useI18n } from '@/i18n';
import type { Locale } from '@/i18n';
import type { NavigationItem } from '../types';

const { locale, t, locales } = useI18n();

const props = defineProps<{
    navigation: NavigationItem[];
}>();

const page = usePage();
const site = computed(
    () => (page.props.site as Record<string, string> | undefined) ?? {},
);

const siteName = computed(() => site.value.site_name || 'CoreCMS');
const initial = computed(() => siteName.value.charAt(0).toUpperCase());
const currentYear = new Date().getFullYear();
const cartCount = computed(
    () => (page.props.cart_count as number | undefined) ?? 0,
);

const socials = computed(() =>
    [
        { label: 'Facebook', url: site.value.facebook },
        { label: 'Instagram', url: site.value.instagram },
        { label: 'LinkedIn', url: site.value.linkedin },
    ].filter((item) => item.url),
);

const isCurrent = (item: NavigationItem): boolean =>
    item.url === page.url || item.url === page.url.split('?')[0];

const navLink = (active: boolean): string =>
    [
        'rounded-md px-3 py-2 text-sm font-medium transition-colors focus-visible:ring-2 focus-visible:ring-blue-700 focus-visible:outline-none',
        active
            ? 'text-blue-800 dark:text-blue-400'
            : 'text-slate-600 hover:bg-slate-100 hover:text-slate-950 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-100',
    ].join(' ');
</script>

<template>
    <div class="flex min-h-screen flex-col bg-slate-50 text-slate-900">
        <header class="sticky top-0 z-40">
            <div
                v-if="site.phone || site.email || site.hours"
                class="hidden border-b border-slate-800 bg-slate-900 text-slate-300 md:block"
            >
                <div
                    class="mx-auto flex h-9 w-full max-w-6xl items-center justify-between gap-4 px-4 text-xs sm:px-6"
                >
                    <div class="flex items-center gap-5">
                        <a
                            v-if="site.phone"
                            :href="`tel:${site.phone}`"
                            class="inline-flex items-center gap-1.5 transition-colors hover:text-white"
                        >
                            <Phone class="size-3.5" aria-hidden="true" />
                            {{ site.phone }}
                        </a>
                        <a
                            v-if="site.email"
                            :href="`mailto:${site.email}`"
                            class="inline-flex items-center gap-1.5 transition-colors hover:text-white"
                        >
                            <Mail class="size-3.5" aria-hidden="true" />
                            {{ site.email }}
                        </a>
                    </div>
                    <p
                        v-if="site.hours"
                        class="inline-flex items-center gap-1.5"
                    >
                        <Clock class="size-3.5" aria-hidden="true" />
                        {{ site.hours }}
                    </p>
                </div>
            </div>

            <div
                class="border-b border-slate-200 bg-white/95 backdrop-blur-sm dark:border-slate-800 dark:bg-slate-950/95"
            >
                <div
                    class="mx-auto flex h-16 w-full max-w-6xl items-center justify-between gap-4 px-4 sm:px-6"
                >
                    <Link
                        href="/"
                        class="flex items-center gap-2.5 text-slate-950 dark:text-slate-50"
                    >
                        <span
                            class="flex size-8 items-center justify-center rounded-md bg-blue-800 text-sm font-semibold text-white"
                            aria-hidden="true"
                        >
                            {{ initial }}
                        </span>
                        <span class="text-lg font-semibold tracking-tight">
                            {{ siteName }}
                        </span>
                    </Link>

                    <nav
                        :aria-label="t('nav.pages')"
                        class="hidden items-center gap-0.5 lg:flex"
                    >
                        <a
                            v-for="item in props.navigation"
                            :key="item.slug"
                            :href="item.url"
                            :aria-current="isCurrent(item) ? 'page' : undefined"
                            :class="navLink(isCurrent(item))"
                        >
                            {{ item.title }}
                        </a>
                        <a
                            href="/blog"
                            :aria-current="
                                page.url.startsWith('/blog') ? 'page' : undefined
                            "
                            :class="navLink(page.url.startsWith('/blog'))"
                        >
                            {{ t('nav.blog') }}
                        </a>
                    </nav>

                    <div class="flex items-center gap-3">
                        <div
                            class="hidden items-center gap-1 sm:flex"
                            role="group"
                            aria-label="Idioma"
                        >
                            <button
                                v-for="option in locales"
                                :key="option.value"
                                type="button"
                                :aria-pressed="locale === option.value"
                                :class="[
                                    'rounded px-1 py-1 text-[11px] font-semibold tracking-wide uppercase transition-colors focus-visible:ring-2 focus-visible:ring-blue-700 focus-visible:outline-none',
                                    locale === option.value
                                        ? 'text-blue-800 dark:text-blue-400'
                                        : 'text-slate-400 hover:text-slate-700 dark:text-slate-500 dark:hover:text-slate-300',
                                ]"
                                @click="switchLocale(option.value as Locale)"
                            >
                                {{ option.short }}
                            </button>
                        </div>

                        <span
                            class="hidden h-4 w-px bg-slate-200 sm:block dark:bg-slate-800"
                            aria-hidden="true"
                        />

                        <a
                            href="/admin"
                            class="hidden text-xs font-medium text-slate-500 transition-colors hover:text-blue-800 md:inline dark:text-slate-400 dark:hover:text-blue-400"
                        >
                            {{ t('nav.panel') }}
                        </a>

                        <a
                            href="/orcamento"
                            class="hidden h-9 items-center gap-2 rounded-md bg-blue-800 px-4 text-sm font-medium text-white transition-colors hover:bg-blue-900 sm:inline-flex"
                        >
                            <ShoppingBag class="size-4" aria-hidden="true" />
                            {{ t('nav.quote') }}
                            <span
                                v-if="cartCount > 0"
                                class="rounded-full bg-white/20 px-1.5 text-[11px] leading-4"
                            >
                                {{ cartCount }}
                            </span>
                        </a>

                        <details class="relative lg:hidden">
                            <summary
                                class="flex size-9 cursor-pointer items-center justify-center rounded-md border border-slate-200 text-slate-600 marker:content-none hover:bg-slate-50 dark:border-slate-800 dark:text-slate-400 dark:hover:bg-slate-900"
                                :aria-label="t('nav.pages')"
                            >
                                <Menu class="size-4" aria-hidden="true" />
                            </summary>
                            <nav
                                class="absolute right-0 z-50 mt-2 flex w-56 flex-col gap-0.5 rounded-md border border-slate-200 bg-white p-2 shadow-lg dark:border-slate-800 dark:bg-slate-900"
                                :aria-label="t('nav.pages')"
                            >
                                <a
                                    v-for="item in props.navigation"
                                    :key="item.slug"
                                    :href="item.url"
                                    class="rounded-md px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800"
                                >
                                    {{ item.title }}
                                </a>
                                <a
                                    href="/blog"
                                    class="rounded-md px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800"
                                >
                                    {{ t('nav.blog') }}
                                </a>
                                <a
                                    href="/orcamento"
                                    class="mt-1 flex items-center justify-center gap-2 rounded-md bg-blue-800 px-3 py-2 text-sm font-medium text-white"
                                >
                                    {{ t('nav.quote') }}
                                    <span
                                        v-if="cartCount > 0"
                                        class="rounded-full bg-white/20 px-1.5 text-[11px] leading-4"
                                    >
                                        {{ cartCount }}
                                    </span>
                                </a>
                                <a
                                    href="/admin"
                                    class="rounded-md px-3 py-2 text-xs font-medium text-slate-500 dark:text-slate-400"
                                >
                                    {{ t('nav.panel') }}
                                </a>
                            </nav>
                        </details>
                    </div>
                </div>
            </div>
        </header>

        <main class="mx-auto w-full max-w-6xl flex-1">
            <slot />
        </main>

        <footer
            class="mt-16 border-t border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-950"
        >
            <div
                class="mx-auto grid w-full max-w-6xl gap-10 px-4 py-12 text-sm text-slate-600 sm:grid-cols-2 sm:px-6 lg:grid-cols-4 dark:text-slate-400"
            >
                <div class="grid content-start gap-3">
                    <p
                        class="flex items-center gap-2 text-base font-semibold text-slate-950 dark:text-slate-50"
                    >
                        <span
                            class="flex size-7 items-center justify-center rounded-md bg-blue-800 text-xs font-semibold text-white"
                            aria-hidden="true"
                        >
                            {{ initial }}
                        </span>
                        {{ siteName }}
                    </p>
                    <p v-if="site.tagline" class="text-slate-500">
                        {{ site.tagline }}
                    </p>
                    <p v-if="site.address">{{ site.address }}</p>
                </div>

                <div class="grid content-start gap-2">
                    <p
                        class="mb-1 text-[11px] font-semibold tracking-[0.12em] text-slate-500 uppercase dark:text-slate-400"
                    >
                        {{ t('footer.navigation') }}
                    </p>
                    <a
                        v-for="item in props.navigation"
                        :key="item.slug"
                        :href="item.url"
                        class="hover:text-blue-800 dark:hover:text-blue-400"
                    >
                        {{ item.title }}
                    </a>
                    <a
                        href="/blog"
                        class="hover:text-blue-800 dark:hover:text-blue-400"
                    >
                        {{ t('nav.blog') }}
                    </a>
                </div>

                <div class="grid content-start gap-2">
                    <p
                        class="mb-1 text-[11px] font-semibold tracking-[0.12em] text-slate-500 uppercase dark:text-slate-400"
                    >
                        {{ t('footer.contact') }}
                    </p>
                    <a
                        v-if="site.phone"
                        :href="`tel:${site.phone}`"
                        class="hover:text-blue-800 dark:hover:text-blue-400"
                    >
                        {{ site.phone }}
                    </a>
                    <a
                        v-if="site.email"
                        :href="`mailto:${site.email}`"
                        class="hover:text-blue-800 dark:hover:text-blue-400"
                    >
                        {{ site.email }}
                    </a>
                    <a
                        v-if="site.whatsapp"
                        :href="`https://api.whatsapp.com/send?phone=55${site.whatsapp}`"
                        target="_blank"
                        rel="noopener"
                        class="hover:text-blue-800 dark:hover:text-blue-400"
                    >
                        WhatsApp
                    </a>
                    <p v-if="site.hours" class="text-slate-500 dark:text-slate-500">
                        {{ site.hours }}
                    </p>
                </div>

                <div class="grid content-start gap-2">
                    <p
                        class="mb-1 text-[11px] font-semibold tracking-[0.12em] text-slate-500 uppercase dark:text-slate-400"
                    >
                        {{ t('footer.social') }}
                    </p>
                    <a
                        v-for="social in socials"
                        :key="social.label"
                        :href="social.url"
                        target="_blank"
                        rel="noopener"
                        class="hover:text-blue-800 dark:hover:text-blue-400"
                    >
                        {{ social.label }}
                    </a>
                    <a
                        href="/orcamento"
                        class="mt-2 inline-flex h-9 w-fit items-center gap-2 rounded-md bg-blue-800 px-3 text-xs font-medium text-white hover:bg-blue-900"
                    >
                        <ShoppingBag class="size-3.5" aria-hidden="true" />
                        {{ t('nav.quote') }}
                    </a>
                </div>
            </div>

            <div class="border-t border-slate-200 dark:border-slate-800">
                <div
                    class="mx-auto flex w-full max-w-6xl flex-col items-start justify-between gap-2 px-4 py-4 text-xs text-slate-500 sm:flex-row sm:items-center sm:px-6 dark:text-slate-500"
                >
                    <p>© {{ currentYear }} {{ siteName }}</p>
                    <a
                        href="/politica-de-cookies"
                        class="hover:text-blue-800 dark:hover:text-blue-400"
                    >
                        {{ t('cookie.policy') }}
                    </a>
                </div>
            </div>
        </footer>

        <CookieConsent />
    </div>
</template>
