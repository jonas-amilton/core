<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import SiteShell from '@modules/Page/resources/assets/js/components/SiteShell.vue';
import type { NavigationItem } from '@modules/Page/resources/assets/js/types';

defineProps<{
    cookieText: string;
    navigation: NavigationItem[];
}>();

const essentials = [
    {
        name: 'session',
        purpose: 'Identifica a sessão de navegação do usuário.',
        duration: '2 horas',
    },
    {
        name: 'XSRF-TOKEN',
        purpose: 'Protege contra ataques de falsificação de requisições.',
        duration: '2 horas',
    },
    {
        name: 'corecms.cookie-consent',
        purpose: 'Armazena as preferências de consentimento de cookies.',
        duration: '1 ano',
    },
];

const optional = [
    {
        name: 'google_analytics',
        purpose: 'Coleta informações anônimas sobre como o site é usado.',
        duration: '6 horas',
    },
    {
        name: 'google_tag_manager',
        purpose: 'Carrega e gerencia tags de análise e marketing.',
        duration: '6 horas',
    },
    {
        name: 'pixel_facebook',
        purpose: 'Mede conversões e o desempenho de anúncios.',
        duration: '6 horas',
    },
];
</script>

<template>
    <Head title="Política de cookies" />

    <SiteShell :navigation="navigation">
        <section class="px-4 py-10 sm:px-6">
            <h1
                class="text-3xl font-semibold tracking-tight text-slate-900 dark:text-slate-100"
            >
                Política de cookies
            </h1>
            <p class="mt-2 max-w-2xl text-sm text-slate-600 dark:text-slate-400">
                {{ cookieText }}
            </p>

            <h2
                class="mt-10 text-lg font-semibold text-slate-900 dark:text-slate-100"
            >
                Cookies essenciais
            </h2>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
                Necessários para o funcionamento do site. Não exigem consentimento.
            </p>
            <div
                class="mt-4 overflow-hidden rounded-xl border border-slate-200 dark:border-slate-800"
            >
                <table class="w-full text-left text-sm">
                    <thead
                        class="border-b border-slate-200 bg-slate-50 text-xs text-slate-500 uppercase dark:border-slate-800 dark:bg-slate-950/60 dark:text-slate-400"
                    >
                        <tr>
                            <th scope="col" class="px-4 py-2.5 font-medium">Cookie</th>
                            <th scope="col" class="px-4 py-2.5 font-medium">Finalidade</th>
                            <th scope="col" class="px-4 py-2.5 font-medium">Duração</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                        <tr v-for="cookie in essentials" :key="cookie.name">
                            <td class="px-4 py-3 font-mono text-xs">{{ cookie.name }}</td>
                            <td class="px-4 py-3 text-slate-600 dark:text-slate-400">
                                {{ cookie.purpose }}
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-500">
                                {{ cookie.duration }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <h2
                class="mt-10 text-lg font-semibold text-slate-900 dark:text-slate-100"
            >
                Cookies opcionais
            </h2>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
                Só são gravados se você aceitar todos ou marcar a categoria
                correspondente em "Personalizar".
            </p>
            <div
                class="mt-4 overflow-hidden rounded-xl border border-slate-200 dark:border-slate-800"
            >
                <table class="w-full text-left text-sm">
                    <thead
                        class="border-b border-slate-200 bg-slate-50 text-xs text-slate-500 uppercase dark:border-slate-800 dark:bg-slate-950/60 dark:text-slate-400"
                    >
                        <tr>
                            <th scope="col" class="px-4 py-2.5 font-medium">Cookie</th>
                            <th scope="col" class="px-4 py-2.5 font-medium">Finalidade</th>
                            <th scope="col" class="px-4 py-2.5 font-medium">Duração</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                        <tr v-for="cookie in optional" :key="cookie.name">
                            <td class="px-4 py-3 font-mono text-xs">{{ cookie.name }}</td>
                            <td class="px-4 py-3 text-slate-600 dark:text-slate-400">
                                {{ cookie.purpose }}
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-500">
                                {{ cookie.duration }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </SiteShell>
</template>
