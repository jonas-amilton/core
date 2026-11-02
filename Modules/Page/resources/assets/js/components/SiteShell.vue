<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { Menu } from '@lucide/vue';
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
        'px-3 py-2 text-[13px] tracking-wide transition-colors focus-visible:ring-2 focus-visible:ring-orange-700 focus-visible:outline-none',
        active
            ? 'text-orange-800 dark:text-orange-400'
            : 'text-stone-600 hover:text-stone-950 dark:text-stone-400 dark:hover:text-stone-100',
    ].join(' ');
</script>

<template>
    <div class="flex min-h-screen flex-col bg-background text-foreground">
        <header
            class="sticky top-0 z-40 border-b border-stone-200 bg-background dark:border-stone-800"
        >
            <div
                class="mx-auto flex h-16 w-full max-w-6xl items-center justify-between gap-4 px-4 sm:px-6"
            >
                <Link
                    href="/"
                    class="flex items-center gap-2.5 text-stone-950 dark:text-stone-50"
                >
                    <span
                        class="size-2 bg-orange-700 dark:bg-orange-500"
                        aria-hidden="true"
                    />
                    <span class="font-display text-2xl leading-none">
                        {{ siteName }}
                    </span>
                </Link>

                <nav
                    :aria-label="t('nav.pages')"
                    class="hidden items-center gap-1 sm:flex"
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
                    <a
                        href="/orcamento"
                        :aria-current="
                            page.url.startsWith('/orcamento')
                                ? 'page'
                                : undefined
                        "
                        :class="navLink(page.url.startsWith('/orcamento'))"
                    >
                        {{ t('nav.quote') }}
                        <span v-if="cartCount > 0" class="text-orange-700">
                            ({{ cartCount }})
                        </span>
                    </a>
                </nav>

                <div class="flex items-center gap-3">
                    <div
                        class="flex items-center gap-1"
                        role="group"
                        aria-label="Idioma"
                    >
                        <button
                            v-for="option in locales"
                            :key="option.value"
                            type="button"
                            :aria-pressed="locale === option.value"
                            :class="[
                                'px-1 py-1 text-[11px] font-medium tracking-[0.12em] uppercase transition-colors focus-visible:ring-2 focus-visible:ring-orange-700 focus-visible:outline-none',
                                locale === option.value
                                    ? 'text-stone-950 dark:text-stone-50'
                                    : 'text-stone-500 hover:text-stone-800 dark:text-stone-400 dark:hover:text-stone-200',
                            ]"
                            @click="switchLocale(option.value as Locale)"
                        >
                            {{ option.short }}
                        </button>
                    </div>

                    <span
                        class="h-4 w-px bg-stone-200 dark:bg-stone-800"
                        aria-hidden="true"
                    />

                    <a
                        href="/admin"
                        class="hidden text-[11px] font-medium tracking-[0.12em] text-stone-500 uppercase transition-colors hover:text-orange-800 sm:inline dark:text-stone-400 dark:hover:text-orange-400"
                    >
                        {{ t('nav.panel') }}
                    </a>

                    <details class="relative sm:hidden">
                        <summary
                            class="flex size-8 cursor-pointer items-center justify-center border border-stone-200 text-stone-600 marker:content-none dark:border-stone-800 dark:text-stone-400"
                            :aria-label="t('nav.pages')"
                        >
                            <Menu class="size-4" aria-hidden="true" />
                        </summary>
                        <nav
                            class="absolute right-0 z-50 mt-2 flex w-48 flex-col border border-stone-200 bg-background py-1 shadow-sm dark:border-stone-800"
                            :aria-label="t('nav.pages')"
                        >
                            <a
                                v-for="item in props.navigation"
                                :key="item.slug"
                                :href="item.url"
                                class="px-3 py-2 text-sm text-stone-700 hover:bg-stone-100 dark:text-stone-300 dark:hover:bg-stone-900"
                            >
                                {{ item.title }}
                            </a>
                            <a
                                href="/blog"
                                class="px-3 py-2 text-sm text-stone-700 hover:bg-stone-100 dark:text-stone-300 dark:hover:bg-stone-900"
                            >
                                {{ t('nav.blog') }}
                            </a>
                            <a
                                href="/orcamento"
                                class="px-3 py-2 text-sm text-stone-700 hover:bg-stone-100 dark:text-stone-300 dark:hover:bg-stone-900"
                            >
                                {{ t('nav.quote') }}
                            </a>
                            <a
                                href="/admin"
                                class="border-t border-stone-200 px-3 py-2 text-sm text-stone-500 dark:border-stone-800 dark:text-stone-400"
                            >
                                {{ t('nav.panel') }}
                            </a>
                        </nav>
                    </details>
                </div>
            </div>
        </header>

        <main class="mx-auto w-full max-w-6xl flex-1">
            <slot />
        </main>

        <footer class="mt-8 border-t border-stone-200 dark:border-stone-800">
            <div
                class="mx-auto grid w-full max-w-6xl gap-8 px-4 py-10 text-sm text-stone-600 sm:grid-cols-3 sm:px-6 dark:text-stone-400"
            >
                <div class="grid content-start gap-2">
                    <p
                        class="font-display text-xl text-stone-950 dark:text-stone-50"
                    >
                        {{ siteName }}
                    </p>
                    <p v-if="site.tagline" class="text-stone-500">
                        {{ site.tagline }}
                    </p>
                    <p v-if="site.address">{{ site.address }}</p>
                    <p v-if="site.hours">{{ site.hours }}</p>
                </div>

                <div class="grid content-start gap-2">
                    <p
                        class="mb-1 text-[11px] font-medium tracking-[0.14em] text-stone-500 uppercase dark:text-stone-400"
                    >
                        Contato
                    </p>
                    <a
                        v-if="site.phone"
                        :href="`tel:${site.phone}`"
                        class="hover:text-orange-800 dark:hover:text-orange-400"
                    >
                        {{ site.phone }}
                    </a>
                    <a
                        v-if="site.email"
                        :href="`mailto:${site.email}`"
                        class="hover:text-orange-800 dark:hover:text-orange-400"
                    >
                        {{ site.email }}
                    </a>
                    <a
                        v-if="site.whatsapp"
                        :href="`https://api.whatsapp.com/send?phone=55${site.whatsapp}`"
                        target="_blank"
                        rel="noopener"
                        class="hover:text-orange-800 dark:hover:text-orange-400"
                    >
                        WhatsApp
                    </a>
                </div>

                <div class="grid content-start gap-2 sm:justify-items-end">
                    <p
                        class="mb-1 text-[11px] font-medium tracking-[0.14em] text-stone-500 uppercase dark:text-stone-400"
                    >
                        Redes
                    </p>
                    <a
                        v-for="social in socials"
                        :key="social.label"
                        :href="social.url"
                        target="_blank"
                        rel="noopener"
                        class="hover:text-orange-800 dark:hover:text-orange-400"
                    >
                        {{ social.label }}
                    </a>
                    <p class="mt-3 text-xs text-stone-500 dark:text-stone-400">
                        © {{ currentYear }} {{ siteName }}
                    </p>
                </div>
            </div>
        </footer>

        <CookieConsent />
    </div>
</template>
