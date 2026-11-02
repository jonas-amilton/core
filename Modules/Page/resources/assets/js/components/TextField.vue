<script setup lang="ts">
import { Input } from '@/components/ui/input';
import { fieldClasses } from '@/lib/formStyles';

withDefaults(
    defineProps<{
        modelValue?: string | null;
        label?: string;
        placeholder?: string;
        type?: string;
        multiline?: boolean;
        rows?: number;
    }>(),
    {
        rows: 4,
    },
);

const emit = defineEmits<{
    'update:modelValue': [value: string];
}>();
</script>

<template>
    <label class="grid gap-1.5">
        <span
            v-if="label"
            class="text-xs font-medium text-stone-600 dark:text-stone-400"
        >
            {{ label }}
        </span>
        <textarea
            v-if="multiline"
            :rows="rows"
            :value="modelValue ?? ''"
            :placeholder="placeholder"
            :class="[fieldClasses, 'h-auto py-2 leading-relaxed']"
            @input="
                emit(
                    'update:modelValue',
                    ($event.target as HTMLTextAreaElement).value,
                )
            "
        />
        <Input
            v-else
            :type="type ?? 'text'"
            :model-value="modelValue ?? ''"
            :placeholder="placeholder"
            :class="fieldClasses"
            @update:model-value="emit('update:modelValue', String($event))"
        />
    </label>
</template>
