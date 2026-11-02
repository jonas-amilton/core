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
    <section class="px-4 pt-6 pb-2 sm:px-6">
        <div
            class="mx-auto grid w-full max-w-6xl items-center gap-10 overflow-hidden rounded-2xl bg-cream px-6 py-10 sm:px-10 sm:py-14 lg:grid-cols-[1.15fr_0.85fr] lg:gap-14 dark:bg-slate-900"
        >
            <div>
                <p
                    class="inline-flex items-center gap-2 rounded-full border border-green-200 bg-cream px-3 py-1 text-xs font-medium text-brand-deep dark:border-brand/30 dark:bg-brand/10 dark:text-green-300"
                >
                    <span
                        class="size-1.5 rounded-full bg-brand dark:bg-green-400"
                        aria-hidden="true"
                    />
                    {{ t('hero.eyebrow') }}
                </p>

                <h1
                    class="mt-5 max-w-2xl text-4xl leading-[1.08] font-bold tracking-tight text-brand text-balance sm:text-5xl lg:text-6xl dark:text-slate-50"
                >
                    {{ block.title }}
                </h1>

                <p
                    v-if="block.subtitle"
                    class="mt-4 max-w-xl text-base leading-relaxed text-slate-700 sm:text-lg dark:text-slate-400"
                >
                    {{ block.subtitle }}
                </p>

                <ul
                    class="mt-6 flex flex-wrap gap-x-5 gap-y-2 text-sm text-slate-700 dark:text-slate-400"
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
                            class="size-4 text-brand dark:text-green-400"
                            aria-hidden="true"
                        />
                        {{ benefit }}
                    </li>
                </ul>

                <div class="mt-8 flex flex-wrap items-center gap-3">
                    <a
                        v-if="block.button_label"
                        :href="block.button_href || '#'"
                        class="inline-flex h-11 items-center gap-2 rounded-full bg-brand px-6 text-sm font-medium text-white transition-colors hover:bg-brand-deep"
                    >
                        {{ block.button_label }}
                        <ArrowRight class="size-4" aria-hidden="true" />
                    </a>
                    <a
                        href="/orcamento"
                        class="inline-flex h-11 items-center rounded-full border border-brand/25 bg-transparent px-6 text-sm font-medium text-brand-deep transition-colors hover:bg-white/60 dark:border-slate-700 dark:text-slate-100 dark:hover:bg-slate-800"
                    >
                        {{ t('hero.secondary') }}
                    </a>
                </div>

                <dl
                    v-if="site.stat_years || site.stat_clients"
                    class="mt-9 flex flex-wrap gap-x-10 gap-y-4 border-t border-brand/15 pt-6 dark:border-slate-800"
                >
                    <div v-if="site.stat_years">
                        <dt
                            class="text-2xl font-semibold text-slate-950 tabular-nums dark:text-slate-50"
                        >
                            {{ site.stat_years }}
                        </dt>
                        <dd class="text-xs text-slate-600 dark:text-slate-400">
                            {{ t('hero.years') }}
                        </dd>
                    </div>
                    <div v-if="site.stat_clients">
                        <dt
                            class="text-2xl font-semibold text-slate-950 tabular-nums dark:text-slate-50"
                        >
                            {{ site.stat_clients }}
                        </dt>
                        <dd class="text-xs text-slate-600 dark:text-slate-400">
                            {{ t('hero.clients') }}
                        </dd>
                    </div>
                </dl>
            </div>

            <aside
                class="rounded-xl bg-white p-6 shadow-sm dark:bg-slate-900"
            >
                <p
                    class="text-[11px] font-semibold tracking-[0.12em] text-brand uppercase dark:text-green-400"
                >
                    {{ t('hero.support') }}
                </p>
                <h2
                    class="mt-2 text-lg font-semibold text-slate-950 dark:text-slate-50"
                >
                    {{ t('hero.supportTitle') }}
                </h2>
                <p class="mt-2 text-sm leading-relaxed text-slate-700 dark:text-slate-400">
                    {{ t('hero.supportText') }}
                </p>

                <ul class="mt-5 grid gap-3 text-sm">
                    <li v-if="site.phone">
                        <a
                            :href="`tel:${site.phone}`"
                            class="flex items-center gap-2.5 text-slate-700 hover:text-brand dark:text-slate-300 dark:hover:text-green-400"
                        >
                            <Phone class="size-4 text-slate-400" aria-hidden="true" />
                            {{ site.phone }}
                        </a>
                    </li>
                    <li v-if="site.email">
                        <a
                            :href="`mailto:${site.email}`"
                            class="flex items-center gap-2.5 text-slate-700 hover:text-brand dark:text-slate-300 dark:hover:text-green-400"
                        >
                            <Mail class="size-4 text-slate-400" aria-hidden="true" />
                            {{ site.email }}
                        </a>
                    </li>
                    <li
                        v-if="site.hours"
                        class="flex items-center gap-2.5 text-slate-700 dark:text-slate-400"
                    >
                        <Clock class="size-4 text-slate-400" aria-hidden="true" />
                        {{ site.hours }}
                    </li>
                </ul>

                <a
                    href="/contato"
                    class="mt-6 inline-flex h-10 w-full items-center justify-center rounded-full bg-brand text-sm font-medium text-white transition-colors hover:bg-brand-deep"
                >
                    {{ t('hero.supportCta') }}
                </a>
            </aside>
        </div>
    </section>
</template>
