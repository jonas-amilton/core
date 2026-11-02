<script setup lang="ts">
import type { Block } from '../types';

defineProps<{
    block: Block;
}>();
</script>

<template>
    <section class="px-4 py-14 sm:px-6 sm:py-20">
        <div class="w-full">
            <h2
                v-if="block.title"
                class="font-display text-3xl tracking-tight text-stone-950 sm:text-4xl dark:text-stone-50"
            >
                {{ block.title }}
            </h2>

            <ul class="mt-10 grid gap-x-10 gap-y-8 sm:grid-cols-2">
                <li
                    v-for="(item, index) in block.items ?? []"
                    :key="index"
                    class="border-t border-stone-300 pt-6 dark:border-stone-700"
                >
                    <p
                        class="font-display text-xl leading-snug text-stone-900 sm:text-2xl dark:text-stone-100"
                    >
                        “{{ item.description }}”
                    </p>
                    <div class="mt-5 flex items-center gap-3">
                        <img
                            v-if="item.avatar"
                            :src="item.avatar"
                            alt=""
                            class="size-9 rounded-full object-cover grayscale"
                        />
                        <div>
                            <p
                                class="text-sm font-medium text-stone-950 dark:text-stone-50"
                            >
                                {{ item.title }}
                            </p>
                            <p
                                v-if="item.role"
                                class="text-xs tracking-wide text-stone-500 dark:text-stone-400"
                            >
                                {{ item.role }}
                            </p>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </section>
</template>
