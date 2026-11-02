<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { Check, Trash2 } from '@lucide/vue';
import { destroy, index as quotesIndex, update } from '@/routes/quote';

type Quote = {
    id: number;
    name: string;
    email: string;
    phone: string | null;
    message: string | null;
    answered: boolean;
    created_at: string | null;
    items: { id: number; name: string; quantity: number }[];
};

const props = defineProps<{
    quotes: Quote[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Orçamentos', href: quotesIndex() }],
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

const toggleAnswered = (item: Quote): void => {
    router.patch(
        update.url(item.id),
        { answered: !item.answered },
        { preserveScroll: true },
    );
};

const remove = (item: Quote): void => {
    if (!window.confirm(`Excluir o orçamento de "${item.name}"?`)) {
        return;
    }

    router.delete(destroy.url(item.id));
};
</script>

<template>
    <Head title="Orçamentos" />

    <div class="flex flex-1 flex-col gap-6 p-4 sm:p-6">
        <header>
            <h1
                class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-slate-100"
            >
                Orçamentos
            </h1>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
                Pedidos enviados pelo carrinho do site.
            </p>
        </header>

        <ul class="grid gap-3">
            <li
                v-for="item in props.quotes"
                :key="item.id"
                class="rounded-xl border border-slate-200 bg-white p-4 dark:border-slate-800 dark:bg-slate-900"
            >
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-slate-900 dark:text-slate-100">
                            {{ item.name }}
                            <span class="font-normal text-slate-500">
                                · {{ item.email }}
                                <template v-if="item.phone">· {{ item.phone }}</template>
                            </span>
                        </p>
                        <p class="mt-1 text-xs text-slate-400">
                            {{ formatDate(item.created_at) }}
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button
                            type="button"
                            :class="[
                                'inline-flex h-8 items-center gap-1 rounded-lg border px-2.5 text-xs font-medium',
                                item.answered
                                    ? 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-300'
                                    : 'border-slate-200 text-slate-600 hover:bg-slate-50 dark:border-slate-800 dark:text-slate-300',
                            ]"
                            @click="toggleAnswered(item)"
                        >
                            <Check class="size-3.5" aria-hidden="true" />
                            {{ item.answered ? 'Respondido' : 'Marcar respondido' }}
                        </button>
                        <button
                            type="button"
                            class="rounded-md p-1 text-slate-400 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-500/10"
                            aria-label="Excluir orçamento"
                            @click="remove(item)"
                        >
                            <Trash2 class="size-3.5" aria-hidden="true" />
                        </button>
                    </div>
                </div>

                <ul class="mt-3 grid gap-1 border-t border-slate-100 pt-3 text-sm dark:border-slate-800">
                    <li
                        v-for="line in item.items"
                        :key="line.id"
                        class="text-slate-600 dark:text-slate-400"
                    >
                        {{ line.quantity }}× {{ line.name }}
                    </li>
                </ul>

                <p
                    v-if="item.message"
                    class="mt-3 whitespace-pre-line border-t border-slate-100 pt-3 text-sm text-slate-600 dark:border-slate-800 dark:text-slate-400"
                >
                    {{ item.message }}
                </p>
            </li>
        </ul>

        <p
            v-if="props.quotes.length === 0"
            class="rounded-xl border border-dashed border-slate-300 px-6 py-12 text-center text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400"
        >
            Nenhum orçamento recebido.
        </p>
    </div>
</template>
