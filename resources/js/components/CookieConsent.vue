<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Cookie, Settings2 } from '@lucide/vue';
import { useI18n } from '@/i18n';

type Consent = {
    essential: true;
    analytics: boolean;
    marketing: boolean;
};

const STORAGE_KEY = 'corecms.cookie-consent';

const visible = ref(false);
const customizing = ref(false);
const analytics = ref(true);
const marketing = ref(true);

const site = usePage().props.site as Record<string, string> | undefined;
const { locale, t } = useI18n();

onMounted(() => {
    if (!localStorage.getItem(STORAGE_KEY)) {
        visible.value = true;
    }
});

function persist(consent: Consent): void {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(consent));
    visible.value = false;
    customizing.value = false;
}

const acceptAll = (): void =>
    persist({ essential: true, analytics: true, marketing: true });

const essentialOnly = (): void =>
    persist({ essential: true, analytics: false, marketing: false });

const savePreferences = (): void =>
    persist({
        essential: true,
        analytics: analytics.value,
        marketing: marketing.value,
    });
</script>

<template>
    <div
        v-if="visible"
        role="region"
        :aria-label="t('cookie.aria')"
        class="fixed inset-x-0 bottom-0 z-50 border-t border-slate-200 bg-white px-4 py-3 shadow-[0_-4px_16px_rgba(0,0,0,0.06)] sm:py-4 dark:border-slate-800 dark:bg-slate-950"
    >
        <div class="mx-auto w-full max-w-5xl">
            <div
                class="flex flex-col items-start gap-3 sm:flex-row sm:items-center sm:justify-between"
            >
                <p
                    class="line-clamp-2 flex items-start gap-2 text-xs text-slate-600 sm:line-clamp-none dark:text-slate-400"
                >
                    <Cookie class="mt-0.5 size-4 shrink-0" aria-hidden="true" />
                    <span>
                        {{
                            (locale === 'pt_BR' && site?.cookie_text) ||
                            t('cookie.text')
                        }}
                        <a
                            href="/politica-de-cookies"
                            class="ml-1 underline hover:text-brand"
                        >
                            {{ t('cookie.policy') }}
                        </a>
                    </span>
                </p>
                <div class="grid w-full shrink-0 grid-cols-3 gap-2 sm:flex sm:w-auto sm:flex-wrap sm:items-center">
                    <button
                        type="button"
                        class="rounded-md border border-slate-300 px-2 py-2 text-xs font-medium text-slate-700 hover:bg-white sm:px-3 sm:py-1.5 dark:border-slate-800 dark:text-slate-200 dark:hover:bg-slate-800"
                        @click="essentialOnly"
                    >
                        {{ t('cookie.onlyEssential') }}
                    </button>
                    <button
                        type="button"
                        class="inline-flex items-center justify-center gap-1 rounded-md border border-slate-300 px-2 py-2 text-xs font-medium text-slate-700 hover:bg-white sm:px-3 sm:py-1.5 dark:border-slate-800 dark:text-slate-200 dark:hover:bg-slate-800"
                        @click="customizing = !customizing"
                    >
                        <Settings2 class="size-3.5" aria-hidden="true" />
                        {{ t('cookie.customize') }}
                    </button>
                    <button
                        type="button"
                        class="rounded-md bg-brand px-2 py-2 text-xs font-medium text-white hover:bg-brand-deep sm:px-3 sm:py-1.5 dark:bg-[#f5f5f5] dark:text-slate-900 dark:hover:bg-slate-200"
                        @click="acceptAll"
                    >
                        {{ t('cookie.acceptAll') }}
                    </button>
                </div>
            </div>

            <div
                v-if="customizing"
                class="mt-3 grid gap-2 rounded-sm border border-slate-200 p-3 text-xs dark:border-slate-800"
            >
                <label
                    class="flex items-start gap-2 text-slate-600 dark:text-slate-400"
                >
                    <input type="checkbox" checked disabled />
                    <span>
                        <strong class="text-slate-800 dark:text-slate-200">{{
                            t('cookie.essential')
                        }}</strong>
                        — {{ t('cookie.essentialDesc') }}
                    </span>
                </label>
                <label
                    class="flex items-start gap-2 text-slate-600 dark:text-slate-400"
                >
                    <input v-model="analytics" type="checkbox" />
                    <span>
                        <strong class="text-slate-800 dark:text-slate-200">{{
                            t('cookie.analytics')
                        }}</strong>
                        — {{ t('cookie.analyticsDesc') }}
                    </span>
                </label>
                <label
                    class="flex items-start gap-2 text-slate-600 dark:text-slate-400"
                >
                    <input v-model="marketing" type="checkbox" />
                    <span>
                        <strong class="text-slate-800 dark:text-slate-200">{{
                            t('cookie.marketing')
                        }}</strong>
                        — {{ t('cookie.marketingDesc') }}
                    </span>
                </label>
                <div>
                    <button
                        type="button"
                        class="rounded-sm bg-slate-900 px-3 py-1.5 text-xs font-medium text-slate-50 hover:bg-slate-800 dark:bg-[#f5f5f5] dark:text-slate-900 dark:hover:bg-slate-200"
                        @click="savePreferences"
                    >
                        {{ t('cookie.save') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
