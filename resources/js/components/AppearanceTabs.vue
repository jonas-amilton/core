<script setup lang="ts">
import { Monitor, Moon, Sun } from '@lucide/vue';
import { useAppearance } from '@/composables/useAppearance';

const { appearance, updateAppearance } = useAppearance();

const tabs = [
    { value: 'light', Icon: Sun, label: 'Claro' },
    { value: 'dark', Icon: Moon, label: 'Escuro' },
    { value: 'system', Icon: Monitor, label: 'Sistema' },
] as const;
</script>

<template>
    <div
        class="inline-flex gap-1 rounded-sm border border-stone-200 bg-stone-100 p-1 dark:border-stone-800 dark:bg-stone-950/60"
    >
        <button
            v-for="{ value, Icon, label } in tabs"
            :key="value"
            type="button"
            :aria-pressed="appearance === value"
            @click="updateAppearance(value)"
            :class="[
                'flex items-center rounded-sm px-3.5 py-1.5 text-sm transition-colors focus-visible:ring-2 focus-visible:ring-orange-600 focus-visible:outline-none',
                appearance === value
                    ? 'bg-white font-medium text-orange-700 dark:bg-stone-800 dark:text-orange-400'
                    : 'text-stone-500 hover:text-stone-900 dark:text-stone-400 dark:hover:text-stone-100',
            ]"
        >
            <component :is="Icon" class="-ml-1 h-4 w-4" aria-hidden="true" />
            <span class="ml-1.5">{{ label }}</span>
        </button>
    </div>
</template>
