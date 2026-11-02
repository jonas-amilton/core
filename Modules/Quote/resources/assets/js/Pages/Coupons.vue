<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import { Power, TicketPercent, Trash2 } from '@lucide/vue';
import { fieldClasses, labelClasses } from '@/lib/formStyles';
const baseUrl = '/painel/orcamentos/cupons';

type Coupon = {
    id: number;
    code: string;
    percent: number;
    active: boolean;
    expires_at: string | null;
    valid: boolean;
};

const props = defineProps<{
    coupons: Coupon[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Cupons', href: baseUrl }],
    },
});

const form = useForm({
    code: '',
    percent: 10,
    expires_at: '',
});

function submit(): void {
    form.post(baseUrl, {
        preserveScroll: true,
        onSuccess: () => form.reset('code', 'expires_at'),
    });
}

function flip(coupon: Coupon): void {
    router.patch(`${baseUrl}/${coupon.id}`, {}, { preserveScroll: true });
}

function remove(coupon: Coupon): void {
    if (!window.confirm(`Excluir o cupom "${coupon.code}"?`)) {
        return;
    }

    router.delete(`${baseUrl}/${coupon.id}`, { preserveScroll: true });
}

const formatDate = (value: string | null): string =>
    value
        ? new Date(value).toLocaleDateString('pt-BR', {
              day: '2-digit',
              month: '2-digit',
              year: 'numeric',
          })
        : 'Sem validade';
</script>

<template>
    <Head title="Cupons" />

    <div class="flex flex-1 flex-col gap-6 p-4 sm:p-6">
        <header>
            <h1
                class="text-2xl font-semibold tracking-tight text-stone-900 dark:text-stone-100"
            >
                Cupons
            </h1>
            <p class="mt-1 text-sm text-stone-600 dark:text-stone-400">
                Desconto percentual aplicado no carrinho do site.
            </p>
        </header>

        <form
            class="flex flex-wrap items-end gap-2"
            @submit.prevent="submit"
        >
            <label class="grid gap-1.5">
                <span :class="labelClasses">Código</span>
                <input
                    v-model="form.code"
                    type="text"
                    placeholder="BEMVINDO10"
                    :class="[fieldClasses, 'w-48 uppercase']"
                />
                <span v-if="form.errors.code" class="text-xs text-red-600">
                    {{ form.errors.code }}
                </span>
            </label>

            <label class="grid gap-1.5">
                <span :class="labelClasses">Desconto (%)</span>
                <input
                    v-model.number="form.percent"
                    type="number"
                    min="1"
                    max="100"
                    :class="[fieldClasses, 'w-28']"
                />
                <span v-if="form.errors.percent" class="text-xs text-red-600">
                    {{ form.errors.percent }}
                </span>
            </label>

            <label class="grid gap-1.5">
                <span :class="labelClasses">Validade (opcional)</span>
                <input
                    v-model="form.expires_at"
                    type="date"
                    :class="[fieldClasses, 'w-44']"
                />
            </label>

            <button
                type="submit"
                class="inline-flex h-10 items-center gap-2 rounded-sm bg-stone-900 px-4 text-sm font-medium text-stone-50 transition-colors hover:bg-stone-800 disabled:opacity-50 dark:bg-stone-100 dark:text-stone-900 dark:hover:bg-stone-200"
                :disabled="form.processing"
            >
                <TicketPercent class="size-4" aria-hidden="true" />
                Criar cupom
            </button>
        </form>

        <div
            v-if="props.coupons.length > 0"
            class="overflow-hidden rounded-sm border border-stone-200 bg-white dark:border-stone-800 dark:bg-stone-900"
        >
            <table class="w-full text-left text-sm">
                <thead
                    class="border-b border-stone-200 bg-stone-50 text-xs text-stone-500 uppercase dark:border-stone-800 dark:bg-stone-950/60 dark:text-stone-400"
                >
                    <tr>
                        <th scope="col" class="px-4 py-2.5 font-medium">Código</th>
                        <th scope="col" class="px-4 py-2.5 font-medium">Desconto</th>
                        <th scope="col" class="hidden px-4 py-2.5 font-medium sm:table-cell">
                            Validade
                        </th>
                        <th scope="col" class="px-4 py-2.5 font-medium">Status</th>
                        <th scope="col" class="px-4 py-2.5 text-right font-medium">
                            Ações
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200 dark:divide-stone-800">
                    <tr
                        v-for="coupon in props.coupons"
                        :key="coupon.id"
                        class="hover:bg-stone-50 dark:hover:bg-stone-800/50"
                    >
                        <td class="px-4 py-3 font-medium text-stone-900 dark:text-stone-100">
                            {{ coupon.code }}
                        </td>
                        <td class="px-4 py-3 text-stone-700 dark:text-stone-300">
                            {{ coupon.percent }}%
                        </td>
                        <td class="hidden px-4 py-3 text-xs text-stone-500 sm:table-cell dark:text-stone-400">
                            {{ formatDate(coupon.expires_at) }}
                        </td>
                        <td class="px-4 py-3 text-xs">
                            <span
                                class="border px-2 py-0.5 font-medium"
                                :class="
                                    coupon.valid
                                        ? 'border-emerald-200 bg-emerald-50 text-emerald-800 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-300'
                                        : 'border-stone-200 bg-stone-50 text-stone-600 dark:border-stone-700 dark:bg-stone-800 dark:text-stone-300'
                                "
                            >
                                {{ coupon.valid ? 'Ativo' : 'Inativo' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="inline-flex items-center gap-1">
                                <button
                                    type="button"
                                    class="rounded-sm p-1 text-stone-400 hover:bg-stone-100 hover:text-stone-700 dark:hover:bg-stone-800"
                                    :aria-label="
                                        coupon.active
                                            ? 'Desativar cupom'
                                            : 'Ativar cupom'
                                    "
                                    @click="flip(coupon)"
                                >
                                    <Power class="size-4" aria-hidden="true" />
                                </button>
                                <button
                                    type="button"
                                    class="rounded-sm p-1 text-stone-400 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-500/10"
                                    aria-label="Excluir cupom"
                                    @click="remove(coupon)"
                                >
                                    <Trash2 class="size-4" aria-hidden="true" />
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
            Nenhum cupom criado ainda.
        </p>
    </div>
</template>
