<script setup lang="ts">
type Props = {
    title: string;
    description?: string;
    variant?: 'default' | 'small';
};

withDefaults(defineProps<Props>(), {
    variant: 'default',
});
</script>

<template>
    <header :class="variant === 'small' ? '' : 'mb-8 space-y-1'">
        <h2
            :class="
                variant === 'small'
                    ? 'text-base font-semibold text-slate-900 dark:text-slate-100'
                    : 'text-xl font-semibold tracking-tight text-slate-900 dark:text-slate-100'
            "
        >
            {{ title }}
        </h2>
        <p
            v-if="description"
            class="text-sm text-slate-600 dark:text-slate-400"
        >
            {{ description }}
        </p>
    </header>
</template>
