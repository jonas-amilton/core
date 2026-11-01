<script setup lang="ts">
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ExternalLink, FileText, Plus, Trash2 } from '@lucide/vue';
import { computed } from 'vue';
import { statusClasses, statusLabels } from '../blocks';
import type { PageSummary } from '../types';
import {
    create,
    destroy,
    edit,
    index as pagesIndex,
    preview,
} from '@/routes/page';

defineProps<{
    pages: PageSummary[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Páginas',
                href: pagesIndex(),
            },
        ],
    },
});

const page = usePage();
const errorMessage = computed(
    () => (page.props.errors as Record<string, string> | undefined)?.page,
);

const formatDate = (value: string | null): string =>
    value
        ? new Date(value).toLocaleString('pt-BR', {
              day: '2-digit',
              month: '2-digit',
              year: '2-digit',
              hour: '2-digit',
              minute: '2-digit',
          })
        : '—';

const publicUrl = (item: PageSummary): string | null =>
    item.status === 'published' ? (item.is_home ? '/' : `/${item.slug}`) : null;

function remove(item: PageSummary): void {
    if (
        !window.confirm(
            `Excluir "${item.title}"? Esta ação não pode ser desfeita.`,
        )
    ) {
        return;
    }

    router.delete(destroy.url(item.id));
}
</script>

<template>
    <Head title="Páginas" />

    <div class="flex flex-1 flex-col gap-6 p-4 sm:p-6">
        <header class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <h1
                    class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-slate-100"
                >
                    Páginas
                </h1>
                <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
                    Crie e edite o conteúdo do site, bloco por bloco.
                </p>
            </div>
            <Link
                :href="create()"
                class="inline-flex h-10 items-center gap-2 rounded-lg bg-indigo-600 px-4 text-sm font-medium text-white transition-colors hover:bg-indigo-500 focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 focus-visible:outline-none dark:focus-visible:ring-offset-slate-950"
            >
                <Plus class="size-4" aria-hidden="true" />
                Nova página
            </Link>
        </header>

        <p
            v-if="errorMessage"
            role="alert"
            class="rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700 dark:border-red-500/30 dark:bg-red-500/10 dark:text-red-300"
        >
            {{ errorMessage }}
        </p>

        <div
            v-if="pages.length > 0"
            class="overflow-hidden rounded-xl border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900"
        >
            <table class="w-full text-left text-sm">
                <thead
                    class="border-b border-slate-200 bg-slate-50 text-xs text-slate-500 uppercase dark:border-slate-800 dark:bg-slate-950/60 dark:text-slate-400"
                >
                    <tr>
                        <th scope="col" class="px-4 py-2.5 font-medium">
                            Título
                        </th>
                        <th
                            scope="col"
                            class="hidden px-4 py-2.5 font-medium sm:table-cell"
                        >
                            Slug
                        </th>
                        <th scope="col" class="px-4 py-2.5 font-medium">
                            Status
                        </th>
                        <th
                            scope="col"
                            class="hidden px-4 py-2.5 font-medium md:table-cell"
                        >
                            Atualizado
                        </th>
                        <th
                            scope="col"
                            class="px-4 py-2.5 text-right font-medium"
                        >
                            Ações
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    <tr
                        v-for="item in pages"
                        :key="item.id"
                        class="transition-colors hover:bg-slate-50 dark:hover:bg-slate-800/50"
                    >
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <Link
                                    :href="edit(item.id)"
                                    class="font-medium text-slate-900 hover:text-indigo-600 dark:text-slate-100 dark:hover:text-indigo-400"
                                >
                                    {{ item.title }}
                                </Link>
                                <span
                                    v-if="item.is_home"
                                    class="rounded-full border border-indigo-200 bg-indigo-50 px-2 py-0.5 text-[11px] font-medium text-indigo-700 dark:border-indigo-500/30 dark:bg-indigo-500/10 dark:text-indigo-300"
                                >
                                    Inicial
                                </span>
                                <span
                                    v-else-if="item.is_not_found"
                                    class="rounded-full border border-slate-200 bg-slate-100 px-2 py-0.5 text-[11px] font-medium text-slate-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300"
                                >
                                    404
                                </span>
                            </div>
                        </td>
                        <td
                            class="hidden px-4 py-3 font-mono text-xs text-slate-500 sm:table-cell dark:text-slate-400"
                        >
                            /{{ item.slug }}
                        </td>
                        <td class="px-4 py-3">
                            <span
                                :class="[
                                    'rounded-full border px-2 py-0.5 text-xs font-medium',
                                    statusClasses[item.status],
                                ]"
                            >
                                {{ statusLabels[item.status] }}
                            </span>
                        </td>
                        <td
                            class="hidden px-4 py-3 text-xs text-slate-500 md:table-cell dark:text-slate-400"
                        >
                            {{ formatDate(item.updated_at) }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="inline-flex items-center gap-1">
                                <Link
                                    :href="edit(item.id)"
                                    class="rounded-md px-2 py-1 text-xs font-medium text-indigo-600 hover:bg-indigo-50 dark:text-indigo-400 dark:hover:bg-indigo-950/40"
                                >
                                    Editar
                                </Link>
                                <a
                                    v-if="publicUrl(item)"
                                    :href="publicUrl(item)!"
                                    target="_blank"
                                    rel="noopener"
                                    class="rounded-md p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-slate-800"
                                    aria-label="Ver página no site"
                                >
                                    <ExternalLink
                                        class="size-3.5"
                                        aria-hidden="true"
                                    />
                                </a>
                                <a
                                    v-else
                                    :href="preview.url(item.id)"
                                    target="_blank"
                                    rel="noopener"
                                    class="rounded-md p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-slate-800"
                                    aria-label="Pré-visualizar rascunho"
                                >
                                    <ExternalLink
                                        class="size-3.5"
                                        aria-hidden="true"
                                    />
                                </a>
                                <button
                                    v-if="!item.is_system"
                                    type="button"
                                    class="rounded-md p-1 text-slate-400 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-500/10"
                                    aria-label="Excluir página"
                                    @click="remove(item)"
                                >
                                    <Trash2
                                        class="size-3.5"
                                        aria-hidden="true"
                                    />
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div
            v-else
            class="flex flex-col items-center justify-center rounded-xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center dark:border-slate-700 dark:bg-slate-900"
        >
            <span
                class="flex size-12 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400"
            >
                <FileText class="size-6" aria-hidden="true" />
            </span>
            <p
                class="mt-4 text-sm font-medium text-slate-900 dark:text-slate-100"
            >
                Nenhuma página ainda
            </p>
            <p class="mt-1 max-w-sm text-sm text-slate-600 dark:text-slate-400">
                Comece com um template e ajuste os blocos depois. Nada é
                publicado sem você mandar.
            </p>
            <Link
                :href="create()"
                class="mt-5 inline-flex h-10 items-center gap-2 rounded-lg bg-indigo-600 px-4 text-sm font-medium text-white transition-colors hover:bg-indigo-500"
            >
                <Plus class="size-4" aria-hidden="true" />
                Criar primeira página
            </Link>
        </div>
    </div>
</template>
