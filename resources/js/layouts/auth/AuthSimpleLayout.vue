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
        class="relative flex min-h-svh flex-col items-center justify-center overflow-hidden bg-white p-6 text-slate-900 antialiased md:p-10 dark:bg-slate-950 dark:text-slate-100"
    >
        <div
            aria-hidden="true"
            class="pointer-events-none absolute inset-0 -z-10 overflow-hidden"
        >
            <div
                class="absolute -top-40 left-1/2 h-96 w-[46rem] -translate-x-1/2 rounded-full bg-indigo-500/20 blur-3xl dark:bg-indigo-500/10"
            />
            <div
                class="absolute right-0 -bottom-24 size-72 rounded-full bg-violet-500/15 blur-3xl dark:bg-violet-500/10"
            />
        </div>

        <div class="w-full max-w-md">
            <div
                class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-xl shadow-slate-900/5 sm:p-8 dark:border-slate-800 dark:bg-slate-900 dark:shadow-black/40"
            >
                <div class="mb-8 flex flex-col items-center gap-4 text-center">
                    <Link
                        :href="home()"
                        aria-label="CoreCMS — voltar para o site"
                        class="flex items-center gap-2.5 rounded-lg focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 focus-visible:outline-none dark:focus-visible:ring-offset-slate-900"
                    >
                        <span
                            class="flex size-10 items-center justify-center rounded-xl bg-linear-to-br from-indigo-500 to-violet-600 text-lg font-semibold text-white shadow-sm"
                        >
                            C
                        </span>
                        <span class="text-lg font-semibold tracking-tight">
                            Core<span
                                class="text-indigo-600 dark:text-indigo-400"
                                >CMS</span
                            >
                        </span>
                    </Link>

                    <div class="space-y-1.5">
                        <h1 class="text-xl font-semibold tracking-tight">
                            {{ title }}
                        </h1>
                        <p
                            class="text-sm text-balance text-slate-600 dark:text-slate-400"
                        >
                            {{ description }}
                        </p>
                    </div>
                </div>

                <slot />
            </div>

            <p class="mt-6 text-center">
                <Link
                    :href="home()"
                    class="inline-flex items-center gap-1.5 rounded-sm text-sm text-slate-500 transition-colors hover:text-indigo-600 focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:outline-none dark:text-slate-400 dark:hover:text-indigo-300"
                >
                    <ArrowLeft class="size-3.5" aria-hidden="true" />
                    Voltar para o site
                </Link>
            </p>
        </div>
    </div>
</template>
