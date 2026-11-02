<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import CookieConsent from '@/components/CookieConsent.vue';
import type { NavigationItem } from '../types';

const props = defineProps<{
    navigation: NavigationItem[];
}>();

const page = usePage();
const site = computed(
    () => (page.props.site as Record<string, string> | undefined) ?? {},
);

const siteName = computed(() => site.value.site_name || 'CoreCMS');
const currentYear = new Date().getFullYear();

const socials = computed(() =>
    [
        { label: 'Facebook', url: site.value.facebook },
        { label: 'Instagram', url: site.value.instagram },
        { label: 'LinkedIn', url: site.value.linkedin },
    ].filter((item) => item.url),
);

const isCurrent = (item: NavigationItem): boolean =>
    item.url === page.url || item.url === page.url.split('?')[0];
</script>

<template>
    <div
        class="flex min-h-screen flex-col bg-white text-slate-900 antialiased dark:bg-slate-950 dark:text-slate-100"
    >
        <header
            class="sticky top-0 z-40 border-b border-slate-200/80 bg-white/90 backdrop-blur-md dark:border-slate-800/80 dark:bg-slate-950/90"
        >
            <div
                class="mx-auto flex h-16 w-full max-w-5xl items-center justify-between gap-4 px-4 sm:px-6"
            >
                <Link
                    href="/"
                    class="text-base font-semibold tracking-tight text-slate-900 dark:text-slate-100"
                >
                    {{ siteName }}
                </Link>

                <nav
                    aria-label="Páginas do site"
                    class="hidden items-center gap-1 sm:flex"
                >
                    <a
                        v-for="item in props.navigation"
                        :key="item.slug"
                        :href="item.url"
                        :aria-current="isCurrent(item) ? 'page' : undefined"
                        :class="[
                            'rounded-md px-3 py-1.5 text-sm transition-colors',
                            isCurrent(item)
                                ? 'bg-indigo-50 font-medium text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400'
                                : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-100',
                        ]"
                    >
                        {{ item.title }}
                    </a>
                    <a
                        href="/blog"
                        class="rounded-md px-3 py-1.5 text-sm text-slate-600 transition-colors hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-100"
                    >
                        Blog
                    </a>
                </nav>

                <a
                    href="/admin"
                    class="rounded-md px-2 py-1 text-xs font-medium text-slate-500 hover:text-indigo-600 dark:text-slate-500 dark:hover:text-indigo-400"
                >
                    Painel
                </a>
            </div>
        </header>

        <main class="mx-auto w-full max-w-5xl flex-1">
            <slot />
        </main>

        <footer class="border-t border-slate-200 dark:border-slate-800">
            <div
                class="mx-auto grid w-full max-w-5xl gap-6 px-4 py-8 text-xs text-slate-500 sm:grid-cols-3 sm:px-6 dark:text-slate-400"
            >
                <div class="grid gap-1.5">
                    <p class="font-medium text-slate-700 dark:text-slate-300">
                        {{ siteName }}
                    </p>
                    <p v-if="site.tagline">{{ site.tagline }}</p>
                    <p v-if="site.address">{{ site.address }}</p>
                    <p v-if="site.hours">{{ site.hours }}</p>
                </div>

                <div class="grid gap-1.5">
                    <a v-if="site.phone" :href="`tel:${site.phone}`">
                        {{ site.phone }}
                    </a>
                    <a v-if="site.email" :href="`mailto:${site.email}`">
                        {{ site.email }}
                    </a>
                    <a
                        v-if="site.whatsapp"
                        :href="`https://api.whatsapp.com/send?phone=55${site.whatsapp}`"
                        target="_blank"
                        rel="noopener"
                    >
                        WhatsApp
                    </a>
                </div>

                <div class="grid content-start gap-1.5">
                    <a
                        v-for="social in socials"
                        :key="social.label"
                        :href="social.url"
                        target="_blank"
                        rel="noopener"
                    >
                        {{ social.label }}
                    </a>
                    <p class="mt-2 text-slate-400 dark:text-slate-500">
                        © {{ currentYear }} {{ siteName }}
                    </p>
                </div>
            </div>
        </footer>

        <CookieConsent />
    </div>
</template>
