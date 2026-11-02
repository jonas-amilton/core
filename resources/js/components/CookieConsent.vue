<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';

const STORAGE_KEY = 'corecms.cookie-consent';

const visible = ref(false);

const site = usePage().props.site as Record<string, string> | undefined;

onMounted(() => {
    if (!localStorage.getItem(STORAGE_KEY)) {
        visible.value = true;
    }
});

function choose(value: 'accepted' | 'rejected'): void {
    localStorage.setItem(STORAGE_KEY, value);
    visible.value = false;
}
</script>

<template>
    <div
        v-if="visible"
        role="region"
        aria-label="Aviso de cookies"
        class="fixed inset-x-0 bottom-0 z-50 border-t border-slate-200 bg-white/95 px-4 py-4 shadow-lg backdrop-blur dark:border-slate-800 dark:bg-slate-950/95"
    >
        <div
            class="mx-auto flex w-full max-w-5xl flex-col items-start gap-3 sm:flex-row sm:items-center sm:justify-between"
        >
            <p class="text-xs text-slate-600 dark:text-slate-400">
                {{
                    site?.cookie_text ||
                    'Este site utiliza cookies para melhorar sua experiência de navegação.'
                }}
            </p>
            <div class="flex shrink-0 items-center gap-2">
                <button
                    type="button"
                    class="rounded-md border border-slate-200 px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:text-slate-200 dark:hover:bg-slate-800"
                    @click="choose('rejected')"
                >
                    Recusar
                </button>
                <button
                    type="button"
                    class="rounded-md bg-indigo-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-indigo-500"
                    @click="choose('accepted')"
                >
                    Aceitar
                </button>
            </div>
        </div>
    </div>
</template>
