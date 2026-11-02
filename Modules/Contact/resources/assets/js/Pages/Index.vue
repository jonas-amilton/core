<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { Download, Mail, Trash2, Users } from '@lucide/vue';
import {
    index as contactIndex,
    destroy as destroyMessage,
} from '@/routes/contact/message';
import {
    destroy as destroySubscriber,
    exportMethod as exportSubscribers,
} from '@/routes/contact/subscriber';

type Message = {
    id: number;
    name: string;
    email: string;
    phone: string | null;
    message: string;
    created_at: string | null;
};

type Subscriber = {
    id: number;
    email: string;
    created_at: string | null;
};

const props = defineProps<{
    messages: Message[];
    subscribers: Subscriber[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Mensagens',
                href: contactIndex(),
            },
        ],
    },
});

const formatDate = (value: string | null): string =>
    value
        ? new Date(value).toLocaleString('pt-BR', {
              day: '2-digit',
              month: '2-digit',
              year: 'numeric',
              hour: '2-digit',
              minute: '2-digit',
          })
        : '—';

function removeMessage(item: Message): void {
    if (!window.confirm(`Excluir a mensagem de "${item.name}"?`)) {
        return;
    }

    router.delete(destroyMessage.url(item.id));
}

function removeSubscriber(item: Subscriber): void {
    if (!window.confirm(`Remover "${item.email}" da newsletter?`)) {
        return;
    }

    router.delete(destroySubscriber.url(item.id));
}
</script>

<template>
    <Head title="Mensagens e newsletter" />

    <div class="flex flex-1 flex-col gap-6 p-4 sm:p-6">
        <header>
            <h1
                class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-slate-100"
            >
                Mensagens e newsletter
            </h1>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
                Contatos enviados pelo site e inscritos na newsletter.
            </p>
        </header>

        <section class="grid gap-3">
            <div class="flex items-center justify-between">
                <h2
                    class="inline-flex items-center gap-2 text-sm font-semibold text-slate-900 dark:text-slate-100"
                >
                    <Mail class="size-4" aria-hidden="true" />
                    Mensagens de contato
                    <span class="text-xs font-normal text-slate-500">
                        ({{ props.messages.length }})
                    </span>
                </h2>
            </div>

            <ul class="grid gap-2">
                <li
                    v-for="item in props.messages"
                    :key="item.id"
                    class="rounded-xl border border-slate-200 bg-white p-4 dark:border-slate-800 dark:bg-slate-900"
                >
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p
                                class="text-sm font-medium text-slate-900 dark:text-slate-100"
                            >
                                {{ item.name }}
                                <span class="font-normal text-slate-500">
                                    · {{ item.email }}
                                    <template v-if="item.phone">
                                        · {{ item.phone }}
                                    </template>
                                </span>
                            </p>
                            <p
                                class="mt-1 text-sm whitespace-pre-line text-slate-600 dark:text-slate-400"
                            >
                                {{ item.message }}
                            </p>
                            <p class="mt-2 text-xs text-slate-400">
                                {{ formatDate(item.created_at) }}
                            </p>
                        </div>
                        <button
                            type="button"
                            class="shrink-0 rounded-md p-1 text-slate-400 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-500/10"
                            aria-label="Excluir mensagem"
                            @click="removeMessage(item)"
                        >
                            <Trash2 class="size-3.5" aria-hidden="true" />
                        </button>
                    </div>
                </li>
            </ul>

            <p
                v-if="props.messages.length === 0"
                class="rounded-xl border border-dashed border-slate-300 px-6 py-10 text-center text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400"
            >
                Nenhuma mensagem recebida.
            </p>
        </section>

        <section class="grid gap-3">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <h2
                    class="inline-flex items-center gap-2 text-sm font-semibold text-slate-900 dark:text-slate-100"
                >
                    <Users class="size-4" aria-hidden="true" />
                    Newsletter
                    <span class="text-xs font-normal text-slate-500">
                        ({{ props.subscribers.length }})
                    </span>
                </h2>
                <a
                    :href="exportSubscribers.url()"
                    class="inline-flex h-9 items-center gap-2 rounded-lg border border-slate-200 px-3 text-sm font-medium text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:text-slate-200 dark:hover:bg-slate-800"
                >
                    <Download class="size-4" aria-hidden="true" />
                    Exportar CSV
                </a>
            </div>

            <div
                v-if="props.subscribers.length > 0"
                class="overflow-hidden rounded-xl border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900"
            >
                <table class="w-full text-left text-sm">
                    <thead
                        class="border-b border-slate-200 bg-slate-50 text-xs text-slate-500 uppercase dark:border-slate-800 dark:bg-slate-950/60 dark:text-slate-400"
                    >
                        <tr>
                            <th scope="col" class="px-4 py-2.5 font-medium">
                                E-mail
                            </th>
                            <th
                                scope="col"
                                class="hidden px-4 py-2.5 font-medium sm:table-cell"
                            >
                                Inscrito em
                            </th>
                            <th
                                scope="col"
                                class="px-4 py-2.5 text-right font-medium"
                            >
                                Ações
                            </th>
                        </tr>
                    </thead>
                    <tbody
                        class="divide-y divide-slate-200 dark:divide-slate-800"
                    >
                        <tr v-for="item in props.subscribers" :key="item.id">
                            <td
                                class="px-4 py-3 text-slate-700 dark:text-slate-300"
                            >
                                {{ item.email }}
                            </td>
                            <td
                                class="hidden px-4 py-3 text-xs text-slate-500 sm:table-cell dark:text-slate-400"
                            >
                                {{ formatDate(item.created_at) }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <button
                                    type="button"
                                    class="rounded-md p-1 text-slate-400 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-500/10"
                                    aria-label="Excluir inscrito"
                                    @click="removeSubscriber(item)"
                                >
                                    <Trash2
                                        class="size-3.5"
                                        aria-hidden="true"
                                    />
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <p
                v-else
                class="rounded-xl border border-dashed border-slate-300 px-6 py-10 text-center text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400"
            >
                Nenhum inscrito ainda.
            </p>
        </section>
    </div>
</template>
