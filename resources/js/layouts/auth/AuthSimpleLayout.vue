<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ArrowLeft } from '@lucide/vue';
import { home } from '@/routes';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div
        class="flex min-h-svh flex-col items-center justify-center bg-background p-6 text-foreground antialiased md:p-10"
    >
        <div class="w-full max-w-md">
            <div class="border-t-2 border-stone-900 pt-8 dark:border-stone-100">
                <div class="mb-8 flex flex-col items-start gap-4">
                    <Link
                        :href="home()"
                        aria-label="CoreCMS — voltar para o site"
                        class="flex items-center gap-2.5 focus-visible:ring-2 focus-visible:ring-orange-700 focus-visible:outline-none"
                    >
                        <span
                            class="flex size-8 items-center justify-center bg-stone-900 font-display text-lg text-stone-50 dark:bg-stone-100 dark:text-stone-900"
                        >
                            C
                        </span>
                        <span
                            class="font-display text-2xl tracking-tight text-stone-950 dark:text-stone-50"
                        >
                            CoreCMS
                        </span>
                    </Link>

                    <div class="space-y-2">
                        <h1
                            class="font-display text-3xl leading-tight tracking-tight text-stone-950 dark:text-stone-50"
                        >
                            {{ title }}
                        </h1>
                        <p
                            class="text-sm text-balance text-stone-600 dark:text-stone-400"
                        >
                            {{ description }}
                        </p>
                    </div>
                </div>
                <slot />
            </div>

            <Link
                :href="home()"
                class="mt-8 inline-flex items-center gap-1.5 text-[11px] font-medium tracking-[0.12em] text-stone-500 uppercase transition-colors hover:text-orange-800 dark:text-stone-400 dark:hover:text-orange-400"
            >
                <ArrowLeft class="size-3.5" aria-hidden="true" />
                Voltar ao site
            </Link>
        </div>
    </div>
</template>
