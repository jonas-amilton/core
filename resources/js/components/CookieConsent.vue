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
        class="fixed inset-x-0 bottom-0 z-50 border-t-2 border-stone-900 bg-background px-4 py-4 dark:border-stone-100"
    >
        <div class="mx-auto w-full max-w-5xl">
            <div
                class="flex flex-col items-start gap-3 sm:flex-row sm:items-center sm:justify-between"
            >
                <p
                    class="flex items-start gap-2 text-xs text-stone-600 dark:text-stone-400"
                >
                    <Cookie class="mt-0.5 size-4 shrink-0" aria-hidden="true" />
                    <span>
                        {{
                            (locale === 'pt_BR' && site?.cookie_text) ||
                            t('cookie.text')
                        }}
                        <a
                            href="/politica-de-cookies"
                            class="ml-1 underline hover:text-orange-700"
                        >
                            {{ t('cookie.policy') }}
                        </a>
                    </span>
                </p>
                <div class="flex shrink-0 flex-wrap items-center gap-2">
                    <button
                        type="button"
                        class="rounded-sm border border-stone-300 px-3 py-1.5 text-xs font-medium text-stone-700 hover:bg-stone-50 dark:border-stone-800 dark:text-stone-200 dark:hover:bg-stone-800"
                        @click="essentialOnly"
                    >
                        {{ t('cookie.onlyEssential') }}
                    </button>
                    <button
                        type="button"
                        class="inline-flex items-center gap-1 rounded-sm border border-stone-300 px-3 py-1.5 text-xs font-medium text-stone-700 hover:bg-stone-50 dark:border-stone-800 dark:text-stone-200 dark:hover:bg-stone-800"
                        @click="customizing = !customizing"
                    >
                        <Settings2 class="size-3.5" aria-hidden="true" />
                        {{ t('cookie.customize') }}
                    </button>
                    <button
                        type="button"
                        class="rounded-sm bg-stone-900 px-3 py-1.5 text-xs font-medium text-stone-50 hover:bg-stone-800 dark:bg-stone-100 dark:text-stone-900 dark:hover:bg-stone-200"
                        @click="acceptAll"
                    >
                        {{ t('cookie.acceptAll') }}
                    </button>
                </div>
            </div>

            <div
                v-if="customizing"
                class="mt-3 grid gap-2 rounded-sm border border-stone-200 p-3 text-xs dark:border-stone-800"
            >
                <label
                    class="flex items-start gap-2 text-stone-600 dark:text-stone-400"
                >
                    <input type="checkbox" checked disabled />
                    <span>
                        <strong class="text-stone-800 dark:text-stone-200">{{
                            t('cookie.essential')
                        }}</strong>
                        — {{ t('cookie.essentialDesc') }}
                    </span>
                </label>
                <label
                    class="flex items-start gap-2 text-stone-600 dark:text-stone-400"
                >
                    <input v-model="analytics" type="checkbox" />
                    <span>
                        <strong class="text-stone-800 dark:text-stone-200">{{
                            t('cookie.analytics')
                        }}</strong>
                        — {{ t('cookie.analyticsDesc') }}
                    </span>
                </label>
                <label
                    class="flex items-start gap-2 text-stone-600 dark:text-stone-400"
                >
                    <input v-model="marketing" type="checkbox" />
                    <span>
                        <strong class="text-stone-800 dark:text-stone-200">{{
                            t('cookie.marketing')
                        }}</strong>
                        — {{ t('cookie.marketingDesc') }}
                    </span>
                </label>
                <div>
                    <button
                        type="button"
                        class="rounded-sm bg-stone-900 px-3 py-1.5 text-xs font-medium text-stone-50 hover:bg-stone-800 dark:bg-stone-100 dark:text-stone-900 dark:hover:bg-stone-200"
                        @click="savePreferences"
                    >
                        {{ t('cookie.save') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
