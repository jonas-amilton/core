<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import {
    ArrowRight,
    ChevronRight,
    FileText,
    Image,
    Plus,
    Upload,
    Users,
} from '@lucide/vue';
import { computed } from 'vue';
import { dashboard } from '@/routes';
import { create as createMedia } from '@/routes/media';
import { create as createPage } from '@/routes/page';
import { index as usersIndex } from '@/routes/user';

type Activity = {
    id: string | number;
    title: string;
    description?: string;
    at: string;
};

const props = defineProps<{
    pagesCount?: number;
    mediaCount?: number;
    adminsCount?: number;
    activities?: Activity[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Dashboard',
                href: dashboard(),
            },
        ],
    },
});

const page = usePage();
const firstName = computed(
    () => page.props.auth?.user?.name?.split(' ')[0] ?? '',
);

const metrics = computed(() => [
    {
        label: 'Páginas publicadas',
        value: props.pagesCount,
        hint: 'Conteúdo publicado no CMS',
        badge: 'No ar',
        badgeClasses:
            'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400',
        icon: FileText,
        tint: 'bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400',
    },
    {
        label: 'Mídias carregadas',
        value: props.mediaCount,
        hint: 'Arquivos na biblioteca',
        badge: 'Biblioteca',
        badgeClasses:
            'bg-violet-50 text-violet-700 dark:bg-violet-500/10 dark:text-violet-300',
        icon: Image,
        tint: 'bg-violet-50 text-violet-600 dark:bg-violet-500/10 dark:text-violet-300',
    },
    {
        label: 'Administradores ativos',
        value: props.adminsCount,
        hint: 'Acessos com permissão total',
        badge: 'Equipe',
        badgeClasses:
            'bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300',
        icon: Users,
        tint: 'bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400',
    },
]);

const quickActions = [
    {
        title: 'Criar nova página',
        description: 'Monte um novo conteúdo com os blocos do CMS',
        href: createPage(),
        icon: Plus,
    },
    {
        title: 'Fazer upload de mídia',
        description: 'Envie imagens e arquivos para a biblioteca',
        href: createMedia(),
        icon: Upload,
    },
    {
        title: 'Gerenciar usuários',
        description: 'Convide administradores e revise permissões',
        href: usersIndex(),
        icon: Users,
    },
];

const recentActivities = computed(() => props.activities ?? []);
</script>

<template>
    <Head title="Dashboard" />

    <div class="flex flex-1 flex-col gap-6 p-4 sm:p-6">
        <header
            class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between"
        >
            <div>
                <h1
                    class="text-2xl font-semibold tracking-tight text-slate-900 sm:text-3xl dark:text-slate-100"
                >
                    Bem-vindo de volta{{ firstName ? `, ${firstName}` : '' }}
                </h1>
                <p
                    class="mt-2 text-sm text-slate-600 sm:text-base dark:text-slate-400"
                >
                    Este é o resumo do seu CMS hoje. Continue de onde parou.
                </p>
            </div>

            <span
                class="inline-flex w-fit items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-700 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-300"
            >
                <span
                    class="size-1.5 rounded-full bg-emerald-500"
                    aria-hidden="true"
                />
                CMS operacional
            </span>
        </header>

        <ul class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <li
                v-for="metric in metrics"
                :key="metric.label"
                class="rounded-xl border border-slate-200 bg-white p-5 shadow-xs dark:border-slate-800 dark:bg-slate-900"
            >
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm text-slate-600 dark:text-slate-400">
                            {{ metric.label }}
                        </p>
                        <p
                            class="mt-2 text-3xl font-semibold tracking-tight text-slate-900 dark:text-slate-100"
                        >
                            {{ metric.value ?? '—' }}
                        </p>
                    </div>
                    <span
                        :class="[
                            'flex size-10 shrink-0 items-center justify-center rounded-lg',
                            metric.tint,
                        ]"
                    >
                        <component
                            :is="metric.icon"
                            class="size-5"
                            aria-hidden="true"
                        />
                    </span>
                </div>
                <div class="mt-4 flex items-center justify-between gap-2">
                    <p class="text-xs text-slate-500 dark:text-slate-500">
                        {{ metric.hint }}
                    </p>
                    <span
                        :class="[
                            'rounded-full px-2 py-0.5 text-[11px] font-medium',
                            metric.badgeClasses,
                        ]"
                    >
                        {{ metric.badge }}
                    </span>
                </div>
            </li>
        </ul>

        <div class="grid gap-6 lg:grid-cols-5">
            <section class="lg:col-span-2">
                <h2
                    class="text-base font-semibold text-slate-900 dark:text-slate-100"
                >
                    Ações rápidas
                </h2>
                <ul class="mt-4 space-y-3">
                    <li v-for="action in quickActions" :key="action.title">
                        <Link
                            :href="action.href"
                            class="group flex items-center gap-4 rounded-xl border border-slate-200 bg-white p-4 transition-colors hover:border-indigo-300 focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:outline-none dark:border-slate-800 dark:bg-slate-900 dark:hover:border-indigo-500/40"
                        >
                            <span
                                class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 transition-colors group-hover:bg-indigo-100 dark:bg-indigo-500/10 dark:text-indigo-400 dark:group-hover:bg-indigo-500/20"
                            >
                                <component
                                    :is="action.icon"
                                    class="size-5"
                                    aria-hidden="true"
                                />
                            </span>
                            <span class="min-w-0 flex-1">
                                <span
                                    class="block text-sm font-medium text-slate-900 dark:text-slate-100"
                                >
                                    {{ action.title }}
                                </span>
                                <span
                                    class="block text-xs text-slate-500 dark:text-slate-400"
                                >
                                    {{ action.description }}
                                </span>
                            </span>
                            <ArrowRight
                                class="size-4 shrink-0 text-slate-400 transition-transform group-hover:translate-x-0.5 group-hover:text-indigo-600 dark:group-hover:text-indigo-400"
                                aria-hidden="true"
                            />
                        </Link>
                    </li>
                </ul>
            </section>

            <section class="lg:col-span-3">
                <div class="flex items-center justify-between gap-3">
                    <h2
                        class="text-base font-semibold text-slate-900 dark:text-slate-100"
                    >
                        Atividades recentes
                    </h2>
                    <Link
                        :href="usersIndex()"
                        class="inline-flex items-center gap-1 rounded-sm text-xs font-medium text-indigo-600 hover:underline focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:outline-none dark:text-indigo-400"
                    >
                        Ver equipe
                        <ChevronRight class="size-3.5" aria-hidden="true" />
                    </Link>
                </div>

                <ul
                    class="mt-4 divide-y divide-slate-200 rounded-xl border border-slate-200 bg-white dark:divide-slate-800 dark:border-slate-800 dark:bg-slate-900"
                >
                    <li
                        v-for="activity in recentActivities"
                        :key="activity.id"
                        class="flex items-start gap-3 p-4"
                    >
                        <span
                            class="mt-1.5 size-2 shrink-0 rounded-full bg-indigo-500"
                            aria-hidden="true"
                        />
                        <div class="min-w-0 flex-1">
                            <p
                                class="text-sm font-medium text-slate-900 dark:text-slate-100"
                            >
                                {{ activity.title }}
                            </p>
                            <p
                                v-if="activity.description"
                                class="mt-0.5 text-xs text-slate-500 dark:text-slate-400"
                            >
                                {{ activity.description }}
                            </p>
                        </div>
                        <span
                            class="shrink-0 text-xs text-slate-400 dark:text-slate-500"
                        >
                            {{ activity.at }}
                        </span>
                    </li>

                    <li
                        v-if="recentActivities.length === 0"
                        class="p-8 text-center"
                    >
                        <p class="text-sm text-slate-600 dark:text-slate-400">
                            Nenhuma atividade registrada ainda.
                        </p>
                        <p
                            class="mt-1 text-xs text-slate-500 dark:text-slate-500"
                        >
                            As ações da sua equipe aparecerão aqui assim que
                            começarem.
                        </p>
                    </li>
                </ul>
            </section>
        </div>
    </div>
</template>
