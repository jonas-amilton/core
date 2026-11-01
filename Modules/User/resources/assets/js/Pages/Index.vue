<script setup lang="ts">
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { Pencil, Plus, Trash2, Users } from '@lucide/vue';
import { computed, ref } from 'vue';
import type { UserItem } from '../types';
import { create, destroy, edit, index as userIndex } from '@/routes/user';

const props = defineProps<{
    users: UserItem[];
    search: string;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Usuários',
                href: userIndex(),
            },
        ],
    },
});

const page = usePage();
const currentUserId = computed(
    () => (page.props.auth?.user as { id?: number } | null)?.id ?? null,
);

const search = ref(props.search);

const formatDate = (value: string | null): string =>
    value
        ? new Date(value).toLocaleDateString('pt-BR', {
              day: '2-digit',
              month: '2-digit',
              year: 'numeric',
          })
        : '—';

function applySearch(): void {
    router.get(
        userIndex().url,
        { search: search.value || undefined },
        { preserveState: true, preserveScroll: true },
    );
}

function remove(item: UserItem): void {
    if (
        !window.confirm(
            `Excluir "${item.name}"? O acesso dessa pessoa ao painel será removido.`,
        )
    ) {
        return;
    }

    router.delete(destroy.url(item.id));
}
</script>

<template>
    <Head title="Usuários" />

    <div class="flex flex-1 flex-col gap-6 p-4 sm:p-6">
        <header class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <h1
                    class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-slate-100"
                >
                    Usuários
                </h1>
                <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
                    Quem pode entrar no painel e editar o site.
                </p>
            </div>
            <Link
                :href="create()"
                class="inline-flex h-10 items-center gap-2 rounded-lg bg-indigo-600 px-4 text-sm font-medium text-white transition-colors hover:bg-indigo-500"
            >
                <Plus class="size-4" aria-hidden="true" />
                Novo usuário
            </Link>
        </header>

        <p
            v-if="$page.props.errors?.user"
            role="alert"
            class="rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700 dark:border-red-500/30 dark:bg-red-500/10 dark:text-red-300"
        >
            {{ $page.props.errors.user }}
        </p>

        <form class="flex gap-2" @submit.prevent="applySearch">
            <input
                v-model="search"
                type="search"
                placeholder="Buscar por nome ou e-mail"
                aria-label="Buscar usuário"
                class="h-10 w-full max-w-sm rounded-lg border border-slate-200 bg-white px-3 text-sm text-slate-900 placeholder:text-slate-400 focus-visible:border-indigo-500 focus-visible:ring-2 focus-visible:ring-indigo-500/30 focus-visible:outline-none dark:border-slate-800 dark:bg-slate-950/60 dark:text-slate-100"
            />
            <button
                type="submit"
                class="h-10 rounded-lg border border-slate-200 px-3 text-sm font-medium text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:text-slate-200 dark:hover:bg-slate-800"
            >
                Buscar
            </button>
        </form>

        <div
            v-if="users.length > 0"
            class="overflow-hidden rounded-xl border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900"
        >
            <table class="w-full text-left text-sm">
                <thead
                    class="border-b border-slate-200 bg-slate-50 text-xs text-slate-500 uppercase dark:border-slate-800 dark:bg-slate-950/60 dark:text-slate-400"
                >
                    <tr>
                        <th scope="col" class="px-4 py-2.5 font-medium">
                            Nome
                        </th>
                        <th scope="col" class="px-4 py-2.5 font-medium">
                            E-mail
                        </th>
                        <th
                            scope="col"
                            class="hidden px-4 py-2.5 font-medium md:table-cell"
                        >
                            Desde
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
                        v-for="item in users"
                        :key="item.id"
                        class="transition-colors hover:bg-slate-50 dark:hover:bg-slate-800/50"
                    >
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <Link
                                    :href="edit(item.id)"
                                    class="font-medium text-slate-900 hover:text-indigo-600 dark:text-slate-100 dark:hover:text-indigo-400"
                                >
                                    {{ item.name }}
                                </Link>
                                <span
                                    v-if="item.id === currentUserId"
                                    class="rounded-full border border-slate-200 bg-slate-100 px-2 py-0.5 text-[11px] font-medium text-slate-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300"
                                >
                                    você
                                </span>
                            </div>
                        </td>
                        <td
                            class="px-4 py-3 text-slate-600 dark:text-slate-400"
                        >
                            {{ item.email }}
                        </td>
                        <td
                            class="hidden px-4 py-3 text-xs text-slate-500 md:table-cell dark:text-slate-400"
                        >
                            {{ formatDate(item.created_at) }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="inline-flex items-center gap-1">
                                <Link
                                    :href="edit(item.id)"
                                    class="rounded-md p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-slate-800"
                                    aria-label="Editar usuário"
                                >
                                    <Pencil
                                        class="size-3.5"
                                        aria-hidden="true"
                                    />
                                </Link>
                                <button
                                    v-if="item.id !== currentUserId"
                                    type="button"
                                    class="rounded-md p-1 text-slate-400 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-500/10"
                                    aria-label="Excluir usuário"
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
                <Users class="size-6" aria-hidden="true" />
            </span>
            <p
                class="mt-4 text-sm font-medium text-slate-900 dark:text-slate-100"
            >
                Nenhum usuário encontrado
            </p>
            <p class="mt-1 max-w-sm text-sm text-slate-600 dark:text-slate-400">
                Crie um usuário para dar acesso ao painel.
            </p>
        </div>
    </div>
</template>
