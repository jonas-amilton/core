<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { FolderTree, Pencil, Plus, Trash2 } from '@lucide/vue';
import { ref } from 'vue';
import {
    create,
    destroy,
    edit,
    index as productsIndex,
} from '@/routes/catalog/product';
import { index as categoryIndex } from '@/routes/catalog/category';
import { fieldClasses } from '@/lib/formStyles';

type Product = {
    id: number;
    name: string;
    slug: string;
    sku: string | null;
    position: number;
    cover: string | null;
    updated_at: string | null;
};

type Paginator = {
    data: Product[];
    current_page: number;
    last_page: number;
    prev_page_url: string | null;
    next_page_url: string | null;
};

type CategoryOption = {
    id: number;
    label: string;
};

const props = defineProps<{
    products: Paginator;
    filters: { search: string; category: number | null };
    categories: CategoryOption[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Produtos', href: productsIndex() }],
    },
});

const search = ref(props.filters.search);
const category = ref<string>(
    props.filters.category ? String(props.filters.category) : '',
);

function applyFilters(): void {
    router.get(
        productsIndex.url(),
        {
            search: search.value || undefined,
            category: category.value || undefined,
        },
        { preserveScroll: true, preserveState: true },
    );
}

function remove(item: Product): void {
    if (!window.confirm(`Excluir o produto "${item.name}"?`)) {
        return;
    }

    router.delete(destroy.url(item.id));
}
</script>

<template>
    <Head title="Produtos" />

    <div class="flex flex-1 flex-col gap-6 p-4 sm:p-6">
        <header class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <h1
                    class="text-2xl font-semibold tracking-tight text-stone-900 dark:text-stone-100"
                >
                    Produtos
                </h1>
                <p class="mt-1 text-sm text-stone-600 dark:text-stone-400">
                    Catálogo exibido em /produtos.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <Link
                    :href="categoryIndex()"
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
                    Novo produto
                </Link>
            </div>
        </header>

        <form
            class="flex flex-wrap items-end gap-2"
            @submit.prevent="applyFilters"
        >
            <label class="grid gap-1.5">
                <span class="text-xs font-medium text-stone-600 dark:text-stone-400">
                    Buscar
                </span>
                <input
                    v-model="search"
                    type="search"
                    placeholder="Nome ou SKU"
                    :class="[fieldClasses, 'w-56']"
                />
            </label>
            <label class="grid gap-1.5">
                <span class="text-xs font-medium text-stone-600 dark:text-stone-400">
                    Categoria
                </span>
                <select v-model="category" :class="[fieldClasses, 'w-56']">
                    <option value="">Todas</option>
                    <option
                        v-for="option in props.categories"
                        :key="option.id"
                        :value="String(option.id)"
                    >
                        {{ option.label }}
                    </option>
                </select>
            </label>
            <button
                type="submit"
                class="h-10 rounded-sm border border-stone-200 px-4 text-sm font-medium text-stone-700 hover:bg-stone-50 dark:border-stone-800 dark:text-stone-200 dark:hover:bg-stone-800"
            >
                Filtrar
            </button>
        </form>

        <div
            v-if="props.products.data.length > 0"
            class="overflow-hidden rounded-sm border border-stone-200 bg-white dark:border-stone-800 dark:bg-stone-900"
        >
            <table class="w-full text-left text-sm">
                <thead
                    class="border-b border-stone-200 bg-stone-50 text-xs text-stone-500 uppercase dark:border-stone-800 dark:bg-stone-950/60 dark:text-stone-400"
                >
                    <tr>
                        <th scope="col" class="px-4 py-2.5 font-medium">Produto</th>
                        <th scope="col" class="hidden px-4 py-2.5 font-medium sm:table-cell">
                            SKU
                        </th>
                        <th scope="col" class="px-4 py-2.5 text-right font-medium">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200 dark:divide-stone-800">
                    <tr
                        v-for="item in props.products.data"
                        :key="item.id"
                        class="transition-colors hover:bg-stone-50 dark:hover:bg-stone-800/50"
                    >
                        <td class="px-4 py-3">
                            <Link
                                :href="edit(item.id)"
                                class="font-medium text-stone-900 hover:text-orange-700 dark:text-stone-100 dark:hover:text-orange-400"
                            >
                                {{ item.name }}
                            </Link>
                            <p class="text-xs text-stone-400">/{{ item.slug }}</p>
                        </td>
                        <td class="hidden px-4 py-3 text-xs text-stone-500 sm:table-cell">
                            {{ item.sku || '—' }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="inline-flex items-center gap-1">
                                <Link
                                    :href="edit(item.id)"
                                    class="rounded-sm p-1 text-stone-400 hover:bg-stone-100 hover:text-stone-700 dark:hover:bg-stone-800"
                                    aria-label="Editar produto"
                                >
                                    <Pencil class="size-3.5" aria-hidden="true" />
                                </Link>
                                <button
                                    type="button"
                                    class="rounded-sm p-1 text-stone-400 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-500/10"
                                    aria-label="Excluir produto"
                                    @click="remove(item)"
                                >
                                    <Trash2 class="size-3.5" aria-hidden="true" />
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
            Nenhum produto encontrado.
        </p>

        <nav
            v-if="props.products.last_page > 1"
            class="flex items-center justify-between text-sm"
            aria-label="Paginação"
        >
            <Link
                v-if="props.products.prev_page_url"
                :href="props.products.prev_page_url"
                class="text-orange-700 hover:underline dark:text-orange-400"
            >
                Anterior
            </Link>
            <span v-else class="text-stone-400">Anterior</span>

            <span class="text-xs text-stone-500">
                Página {{ props.products.current_page }} de
                {{ props.products.last_page }}
            </span>

            <Link
                v-if="props.products.next_page_url"
                :href="props.products.next_page_url"
                class="text-orange-700 hover:underline dark:text-orange-400"
            >
                Próxima
            </Link>
            <span v-else class="text-stone-400">Próxima</span>
        </nav>
    </div>
</template>
