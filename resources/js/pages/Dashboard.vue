<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ArrowRight, FileText, Image, Plus, Upload, Users } from '@lucide/vue';
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
        hint: 'Conteúdo no ar',
        icon: FileText,
    },
    {
        label: 'Mídias carregadas',
        value: props.mediaCount,
        hint: 'Arquivos na biblioteca',
        icon: Image,
    },
    {
        label: 'Administradores',
        value: props.adminsCount,
        hint: 'Acessos com permissão total',
        icon: Users,
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

    <div class="flex flex-1 flex-col gap-10 p-4 sm:p-8">
        <header
            class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between"
        >
            <div>
                <p
                    class="text-[11px] font-medium tracking-[0.16em] text-stone-500 uppercase dark:text-stone-400"
                >
                    Visão geral
                </p>
                <h1
                    class="mt-3 font-display text-3xl leading-tight tracking-tight text-stone-950 sm:text-4xl dark:text-stone-50"
                >
                    Bem-vindo de volta{{ firstName ? `, ${firstName}` : '' }}.
                </h1>
                <p class="mt-2 text-sm text-stone-600 dark:text-stone-400">
                    Este é o resumo do seu CMS hoje. Continue de onde parou.
                </p>
            </div>

            <p
                class="inline-flex w-fit items-center gap-2 text-[11px] font-medium tracking-[0.14em] text-stone-500 uppercase dark:text-stone-400"
            >
                <span
                    class="size-1.5 rounded-full bg-emerald-600 dark:bg-emerald-400"
                    aria-hidden="true"
                />
                CMS operacional
            </p>
        </header>

        <ul
            class="grid border-t border-stone-200 sm:grid-cols-3 dark:border-stone-800"
        >
            <li
                v-for="(metric, index) in metrics"
                :key="metric.label"
                class="border-b border-stone-200 py-6 sm:border-r sm:border-b-0 sm:pr-6 dark:border-stone-800"
                :class="index > 0 ? 'sm:pl-6' : ''"
            >
                <div class="flex items-center justify-between gap-3">
                    <p
                        class="text-[11px] font-medium tracking-[0.14em] text-stone-500 uppercase dark:text-stone-400"
                    >
                        {{ metric.label }}
                    </p>
                    <component
                        :is="metric.icon"
                        class="size-4 text-stone-400 dark:text-stone-500"
                        aria-hidden="true"
                    />
                </div>
                <p
                    class="mt-4 font-display text-5xl leading-none tracking-tight text-stone-950 tabular-nums dark:text-stone-50"
                >
                    {{ metric.value ?? '—' }}
                </p>
                <p class="mt-3 text-xs text-stone-500 dark:text-stone-400">
                    {{ metric.hint }}
                </p>
            </li>
        </ul>

        <div class="grid gap-10 lg:grid-cols-5">
            <section class="lg:col-span-2">
                <h2
                    class="text-[11px] font-medium tracking-[0.16em] text-stone-500 uppercase dark:text-stone-400"
                >
                    Ações rápidas
                </h2>
                <ul
                    class="mt-2 border-t border-stone-200 dark:border-stone-800"
                >
                    <li v-for="action in quickActions" :key="action.title">
                        <Link
                            :href="action.href"
                            class="group flex items-center gap-4 border-b border-stone-200 py-4 transition-colors hover:bg-stone-100/60 focus-visible:ring-2 focus-visible:ring-orange-700 focus-visible:outline-none dark:border-stone-800 dark:hover:bg-stone-900/60"
                        >
                            <component
                                :is="action.icon"
                                class="size-4 shrink-0 text-stone-400 transition-colors group-hover:text-orange-800 dark:group-hover:text-orange-400"
                                aria-hidden="true"
                            />
                            <span class="min-w-0 flex-1">
                                <span
                                    class="block text-sm font-medium text-stone-950 dark:text-stone-50"
                                >
                                    {{ action.title }}
                                </span>
                                <span
                                    class="block text-xs text-stone-500 dark:text-stone-400"
                                >
                                    {{ action.description }}
                                </span>
                            </span>
                            <ArrowRight
                                class="size-4 shrink-0 text-stone-400 transition-transform group-hover:translate-x-0.5"
                                aria-hidden="true"
                            />
                        </Link>
                    </li>
                </ul>
            </section>

            <section class="lg:col-span-3">
                <div class="flex items-end justify-between gap-3">
                    <h2
                        class="text-[11px] font-medium tracking-[0.16em] text-stone-500 uppercase dark:text-stone-400"
                    >
                        Atividades recentes
                    </h2>
                    <Link
                        :href="usersIndex()"
                        class="text-[11px] font-medium tracking-[0.12em] text-orange-800 uppercase underline-offset-4 hover:underline focus-visible:ring-2 focus-visible:ring-orange-700 focus-visible:outline-none dark:text-orange-400"
                    >
                        Ver equipe
                    </Link>
                </div>

                <ul
                    class="mt-2 border-t border-stone-200 dark:border-stone-800"
                >
                    <li
                        v-for="activity in recentActivities"
                        :key="activity.id"
                        class="flex items-baseline gap-3 border-b border-stone-200 py-4 dark:border-stone-800"
                    >
                        <span
                            class="h-1.5 w-1.5 shrink-0 translate-y-[-2px] rounded-full bg-orange-700 dark:bg-orange-500"
                            aria-hidden="true"
                        />
                        <div class="min-w-0 flex-1">
                            <p
                                class="text-sm font-medium text-stone-950 dark:text-stone-50"
                            >
                                {{ activity.title }}
                            </p>
                            <p
                                v-if="activity.description"
                                class="mt-0.5 text-xs text-stone-500 dark:text-stone-400"
                            >
                                {{ activity.description }}
                            </p>
                        </div>
                        <span
                            class="shrink-0 text-[11px] text-stone-400 tabular-nums dark:text-stone-500"
                        >
                            {{ activity.at }}
                        </span>
                    </li>

                    <li
                        v-if="recentActivities.length === 0"
                        class="border-b border-stone-200 py-10 dark:border-stone-800"
                    >
                        <p class="text-sm text-stone-600 dark:text-stone-400">
                            Nenhuma atividade registrada ainda.
                        </p>
                        <p
                            class="mt-1 text-xs text-stone-500 dark:text-stone-500"
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
