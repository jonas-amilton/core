<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { FolderTree, Pencil, Plus, Trash2 } from '@lucide/vue';
import { create, destroy, edit, index as blogIndex } from '@/routes/blog';
import { index as categoriesIndex } from '@/routes/blog/category';

type Post = {
    id: number;
    title: string;
    slug: string;
    category_id: number | null;
    published_at: string | null;
    cover: string | null;
    updated_at: string | null;
};

const props = defineProps<{
    posts: Post[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Blog',
                href: blogIndex(),
            },
        ],
    },
});

const formatDate = (value: string | null): string =>
    value
        ? new Date(value).toLocaleDateString('pt-BR', {
              day: '2-digit',
              month: '2-digit',
              year: 'numeric',
          })
        : '—';

const isPublished = (post: Post): boolean =>
    post.published_at !== null && new Date(post.published_at) <= new Date();

function remove(post: Post): void {
    if (!window.confirm(`Excluir o artigo "${post.title}"?`)) {
        return;
    }

    router.delete(destroy.url(post.id));
}
</script>

<template>
    <Head title="Blog" />

    <div class="flex flex-1 flex-col gap-6 p-4 sm:p-6">
        <header class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <h1
                    class="text-2xl font-semibold tracking-tight text-stone-900 dark:text-stone-100"
                >
                    Blog
                </h1>
                <p class="mt-1 text-sm text-stone-600 dark:text-stone-400">
                    Artigos publicados em /blog.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <Link
                    :href="categoriesIndex()"
                    class="inline-flex h-10 items-center gap-2 rounded-sm border border-stone-200 px-4 text-sm font-medium text-stone-700 hover:bg-stone-50 dark:border-stone-800 dark:text-stone-200 dark:hover:bg-stone-800"
                >
                    <FolderTree class="size-4" aria-hidden="true" />
                    Categorias
                </Link>
                <Link
                    :href="create()"
                    class="inline-flex h-10 items-center gap-2 rounded-sm bg-stone-900 px-4 text-sm font-medium text-stone-50 dark:text-stone-900 dark:bg-stone-100 dark:hover:bg-stone-200 transition-colors hover:bg-stone-800"
                >
                    <Plus class="size-4" aria-hidden="true" />
                    Novo artigo
                </Link>
            </div>
        </header>

        <div
            v-if="props.posts.length > 0"
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
                        v-for="item in props.posts"
                        :key="item.id"
                        class="transition-colors hover:bg-stone-50 dark:hover:bg-stone-800/50"
                    >
                        <td class="px-4 py-3">
                            <Link
                                :href="edit(item.id)"
                                class="font-medium text-stone-900 hover:text-orange-700 dark:text-stone-100 dark:hover:text-orange-400"
                            >
                                {{ item.title }}
                            </Link>
                            <p class="text-xs text-stone-400">
                                /{{ item.slug }}
                            </p>
                        </td>
                        <td class="hidden px-4 py-3 sm:table-cell">
                            <span
                                :class="[
                                    'border px-2 py-0.5 text-[11px] font-medium',
                                    isPublished(item)
                                        ? 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-300'
                                        : 'border-stone-200 bg-stone-100 text-stone-600 dark:border-stone-700 dark:bg-stone-800 dark:text-stone-300',
                                ]"
                            >
                                {{
                                    isPublished(item) ? 'Publicado' : 'Rascunho'
                                }}
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
                                    class="rounded-sm p-1 text-stone-400 hover:bg-stone-100 hover:text-stone-700 dark:hover:bg-stone-800"
                                    aria-label="Editar artigo"
                                >
                                    <Pencil
                                        class="size-3.5"
                                        aria-hidden="true"
                                    />
                                </Link>
                                <button
                                    type="button"
                                    class="rounded-sm p-1 text-stone-400 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-500/10"
                                    aria-label="Excluir artigo"
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

        <p
            v-else
            class="rounded-sm border border-dashed border-stone-300 px-6 py-16 text-center text-sm text-stone-500 dark:border-stone-700 dark:text-stone-400"
        >
            Nenhum artigo ainda. Crie o primeiro.
        </p>
    </div>
</template>
