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
                    class="text-2xl font-semibold tracking-tight text-stone-900 dark:text-stone-100"
                >
                    Páginas
                </h1>
                <p class="mt-1 text-sm text-stone-600 dark:text-stone-400">
                    Crie e edite o conteúdo do site, bloco por bloco.
                </p>
            </div>
            <Link
                :href="create()"
                class="inline-flex h-10 items-center gap-2 rounded-sm bg-stone-900 px-4 text-sm font-medium text-stone-50 dark:text-stone-900 dark:bg-stone-100 dark:hover:bg-stone-200 transition-colors hover:bg-stone-800 focus-visible:ring-2 focus-visible:ring-orange-600 focus-visible:ring-offset-2 focus-visible:outline-none dark:focus-visible:ring-offset-stone-950"
            >
                <Plus class="size-4" aria-hidden="true" />
                Nova página
            </Link>
        </header>

        <p
            v-if="errorMessage"
            role="alert"
            class="rounded-sm border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700 dark:border-red-500/30 dark:bg-red-500/10 dark:text-red-300"
        >
            {{ errorMessage }}
        </p>

        <div
            v-if="pages.length > 0"
            class="overflow-hidden rounded-sm border border-stone-200 bg-white dark:border-stone-800 dark:bg-stone-900"
        >
            <table class="w-full text-left text-sm">
                <thead
                    class="border-b border-stone-200 bg-stone-50 text-xs text-stone-500 uppercase dark:border-stone-800 dark:bg-stone-950/60 dark:text-stone-400"
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
                <tbody class="divide-y divide-stone-200 dark:divide-stone-800">
                    <tr
                        v-for="item in pages"
                        :key="item.id"
                        class="transition-colors hover:bg-stone-50 dark:hover:bg-stone-800/50"
                    >
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <Link
                                    :href="edit(item.id)"
                                    class="font-medium text-stone-900 hover:text-orange-700 dark:text-stone-100 dark:hover:text-orange-400"
                                >
                                    {{ item.title }}
                                </Link>
                                <span
                                    v-if="item.is_home"
                                    class="border border-orange-200 bg-orange-50 px-2 py-0.5 text-[11px] font-medium text-orange-800 dark:border-stone-700 dark:bg-orange-600/10 dark:text-orange-300"
                                >
                                    Inicial
                                </span>
                                <span
                                    v-else-if="item.is_not_found"
                                    class="border border-stone-200 bg-stone-100 px-2 py-0.5 text-[11px] font-medium text-stone-600 dark:border-stone-700 dark:bg-stone-800 dark:text-stone-300"
                                >
                                    404
                                </span>
                            </div>
                        </td>
                        <td
                            class="hidden px-4 py-3 font-mono text-xs text-stone-500 sm:table-cell dark:text-stone-400"
                        >
                            /{{ item.slug }}
                        </td>
                        <td class="px-4 py-3">
                            <span
                                :class="[
                                    'border px-2 py-0.5 text-xs font-medium',
                                    statusClasses[item.status],
                                ]"
                            >
                                {{ statusLabels[item.status] }}
                            </span>
                        </td>
                        <td
                            class="hidden px-4 py-3 text-xs text-stone-500 md:table-cell dark:text-stone-400"
                        >
                            {{ formatDate(item.updated_at) }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="inline-flex items-center gap-1">
                                <Link
                                    :href="edit(item.id)"
                                    class="rounded-sm px-2 py-1 text-xs font-medium text-orange-700 hover:bg-orange-50 dark:text-orange-400 dark:hover:bg-orange-950/40"
                                >
                                    Editar
                                </Link>
                                <a
                                    v-if="publicUrl(item)"
                                    :href="publicUrl(item)!"
                                    target="_blank"
                                    rel="noopener"
                                    class="rounded-sm p-1 text-stone-400 hover:bg-stone-100 hover:text-stone-700 dark:hover:bg-stone-800"
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
                                    class="rounded-sm p-1 text-stone-400 hover:bg-stone-100 hover:text-stone-700 dark:hover:bg-stone-800"
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
                                    class="rounded-sm p-1 text-stone-400 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-500/10"
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
            class="flex flex-col items-center justify-center rounded-sm border border-dashed border-stone-300 bg-white px-6 py-16 text-center dark:border-stone-700 dark:bg-stone-900"
        >
            <span
                class="flex size-12 items-center justify-center rounded-sm bg-orange-50 text-orange-700 dark:bg-orange-600/10 dark:text-orange-400"
            >
                <FileText class="size-6" aria-hidden="true" />
            </span>
            <p
                class="mt-4 text-sm font-medium text-stone-900 dark:text-stone-100"
            >
                Nenhuma página ainda
            </p>
            <p class="mt-1 max-w-sm text-sm text-stone-600 dark:text-stone-400">
                Comece com um template e ajuste os blocos depois. Nada é
                publicado sem você mandar.
            </p>
            <Link
                :href="create()"
                class="mt-5 inline-flex h-10 items-center gap-2 rounded-sm bg-stone-900 px-4 text-sm font-medium text-stone-50 dark:text-stone-900 dark:bg-stone-100 dark:hover:bg-stone-200 transition-colors hover:bg-stone-800"
            >
                <Plus class="size-4" aria-hidden="true" />
                Criar primeira página
            </Link>
        </div>
    </div>
</template>
