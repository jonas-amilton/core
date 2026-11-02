<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { ArrowRight, Check, Clock, Mail, Phone } from '@lucide/vue';
import { computed } from 'vue';
import { useI18n } from '@/i18n';
import type { Block } from '../types';

const { t } = useI18n();

defineProps<{ block: Block }>();

const page = usePage();
const site = computed(
    () => (page.props.site as Record<string, string> | undefined) ?? {},
);
</script>

<template>
    <section class="border-b border-slate-200 dark:border-slate-800">
        <div
            class="mx-auto grid w-full max-w-6xl items-start gap-10 px-4 py-12 sm:px-6 sm:py-16 lg:grid-cols-[1.15fr_0.85fr] lg:gap-16 lg:py-20"
        >
            <div>
                <p
                    class="inline-flex items-center gap-2 rounded-full border border-blue-200 bg-blue-50 px-3 py-1 text-xs font-medium text-blue-800 dark:border-blue-500/30 dark:bg-blue-500/10 dark:text-blue-300"
                >
                    <span
                        class="size-1.5 rounded-full bg-blue-700 dark:bg-blue-400"
                        aria-hidden="true"
                    />
                    {{ t('hero.eyebrow') }}
                </p>

                <h1
                    class="mt-5 max-w-2xl text-3xl leading-[1.12] font-semibold tracking-tight text-slate-950 text-balance sm:text-4xl lg:text-5xl dark:text-slate-50"
                >
                    {{ block.title }}
                </h1>

                <p
                    v-if="block.subtitle"
                    class="mt-4 max-w-xl text-base leading-relaxed text-slate-600 sm:text-lg dark:text-slate-400"
                >
                    {{ block.subtitle }}
                </p>

                <ul
                    class="mt-6 flex flex-wrap gap-x-5 gap-y-2 text-sm text-slate-600 dark:text-slate-400"
                >
                    <li
                        v-for="benefit in [
                            t('hero.benefit1'),
                            t('hero.benefit2'),
                            t('hero.benefit3'),
                        ]"
                        :key="benefit"
                        class="inline-flex items-center gap-1.5"
                    >
                        <Check
                            class="size-4 text-blue-700 dark:text-blue-400"
                            aria-hidden="true"
                        />
                        {{ benefit }}
                    </li>
                </ul>

                <div class="mt-8 flex flex-wrap items-center gap-3">
                    <a
                        v-if="block.button_label"
                        :href="block.button_href || '#'"
                        class="inline-flex h-11 items-center gap-2 rounded-md bg-blue-800 px-5 text-sm font-medium text-white transition-colors hover:bg-blue-900"
                    >
                        {{ block.button_label }}
                        <ArrowRight class="size-4" aria-hidden="true" />
                    </a>
                    <a
                        href="/orcamento"
                        class="inline-flex h-11 items-center rounded-md border border-slate-300 bg-white px-5 text-sm font-medium text-slate-800 transition-colors hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800"
                    >
                        {{ t('hero.secondary') }}
                    </a>
                </div>

                <dl
                    v-if="site.stat_years || site.stat_clients"
                    class="mt-9 flex flex-wrap gap-x-10 gap-y-4 border-t border-slate-200 pt-6 dark:border-slate-800"
                >
                    <div v-if="site.stat_years">
                        <dt
                            class="text-2xl font-semibold text-slate-950 tabular-nums dark:text-slate-50"
                        >
                            {{ site.stat_years }}
                        </dt>
                        <dd class="text-xs text-slate-500 dark:text-slate-400">
                            {{ t('hero.years') }}
                        </dd>
                    </div>
                    <div v-if="site.stat_clients">
                        <dt
                            class="text-2xl font-semibold text-slate-950 tabular-nums dark:text-slate-50"
                        >
                            {{ site.stat_clients }}
                        </dt>
                        <dd class="text-xs text-slate-500 dark:text-slate-400">
                            {{ t('hero.clients') }}
                        </dd>
                    </div>
                </dl>
            </div>

            <aside
                class="rounded-lg border border-slate-200 bg-white p-6 shadow-xs dark:border-slate-800 dark:bg-slate-900"
            >
                <p
                    class="text-[11px] font-semibold tracking-[0.12em] text-blue-800 uppercase dark:text-blue-400"
                >
                    {{ t('hero.support') }}
                </p>
                <h2
                    class="mt-2 text-lg font-semibold text-slate-950 dark:text-slate-50"
                >
                    {{ t('hero.supportTitle') }}
                </h2>
                <p class="mt-2 text-sm leading-relaxed text-slate-600 dark:text-slate-400">
                    {{ t('hero.supportText') }}
                </p>

                <ul class="mt-5 grid gap-3 text-sm">
                    <li v-if="site.phone">
                        <a
                            :href="`tel:${site.phone}`"
                            class="flex items-center gap-2.5 text-slate-700 hover:text-blue-800 dark:text-slate-300 dark:hover:text-blue-400"
                        >
                            <Phone class="size-4 text-slate-400" aria-hidden="true" />
                            {{ site.phone }}
                        </a>
                    </li>
                    <li v-if="site.email">
                        <a
                            :href="`mailto:${site.email}`"
                            class="flex items-center gap-2.5 text-slate-700 hover:text-blue-800 dark:text-slate-300 dark:hover:text-blue-400"
                        >
                            <Mail class="size-4 text-slate-400" aria-hidden="true" />
                            {{ site.email }}
                        </a>
                    </li>
                    <li
                        v-if="site.hours"
                        class="flex items-center gap-2.5 text-slate-600 dark:text-slate-400"
                    >
                        <Clock class="size-4 text-slate-400" aria-hidden="true" />
                        {{ site.hours }}
                    </li>
                </ul>

                <a
                    href="/contato"
                    class="mt-6 inline-flex h-10 w-full items-center justify-center rounded-md border border-slate-300 text-sm font-medium text-slate-800 transition-colors hover:border-blue-300 hover:text-blue-800 dark:border-slate-700 dark:text-slate-200 dark:hover:border-blue-500/40 dark:hover:text-blue-400"
                >
                    {{ t('hero.supportCta') }}
                </a>
            </aside>
        </div>
    </section>
</template>
