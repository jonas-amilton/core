<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { Check, Star, Trash2, X } from '@lucide/vue';
import {
    destroy,
    index as reviewsIndex,
    update,
} from '@/routes/catalog/review';

type Review = {
    id: number;
    name: string;
    email: string;
    rating: number;
    body: string;
    approved: boolean;
    created_at: string | null;
    product: string | null;
};

const props = defineProps<{
    reviews: Review[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Avaliações', href: reviewsIndex() }],
    },
});

function toggle(review: Review): void {
    router.patch(update.url(review.id), {}, { preserveScroll: true });
}

function remove(review: Review): void {
    if (!window.confirm(`Excluir a avaliação de "${review.name}"?`)) {
        return;
    }

    router.delete(destroy.url(review.id), { preserveScroll: true });
}
</script>

<template>
    <Head title="Avaliações" />

    <div class="flex flex-1 flex-col gap-6 p-4 sm:p-6">
        <header>
            <h1
                class="text-2xl font-semibold tracking-tight text-stone-900 dark:text-stone-100"
            >
                Avaliações
            </h1>
            <p class="mt-1 text-sm text-stone-600 dark:text-stone-400">
                Só avaliações aprovadas aparecem na página do produto.
            </p>
        </header>

        <ul v-if="props.reviews.length > 0" class="grid gap-3">
            <li
                v-for="review in props.reviews"
                :key="review.id"
                class="rounded-sm border border-stone-200 bg-white p-4 dark:border-stone-800 dark:bg-stone-900"
            >
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-stone-900 dark:text-stone-100">
                            {{ review.name }}
                            <span class="text-amber-500">
                                {{ '★'.repeat(review.rating) }}
                            </span>
                        </p>
                        <p class="text-xs text-stone-500 dark:text-stone-400">
                            {{ review.product }} · {{ review.email }}
                        </p>
                    </div>

                    <div class="flex items-center gap-1.5">
                        <span
                            class="border px-2 py-0.5 text-[11px] font-medium"
                            :class="
                                review.approved
                                    ? 'border-emerald-200 bg-emerald-50 text-emerald-800 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-300'
                                    : 'border-stone-200 bg-stone-50 text-stone-600 dark:border-stone-700 dark:bg-stone-800 dark:text-stone-300'
                            "
                        >
                            {{ review.approved ? 'Publicada' : 'Pendente' }}
                        </span>

                        <button
                            type="button"
                            class="rounded-sm p-1 text-stone-400 hover:bg-stone-100 hover:text-stone-700 dark:hover:bg-stone-800"
                            :aria-label="
                                review.approved
                                    ? 'Despublicar avaliação'
                                    : 'Aprovar avaliação'
                            "
                            @click="toggle(review)"
                        >
                            <X v-if="review.approved" class="size-4" aria-hidden="true" />
                            <Check v-else class="size-4" aria-hidden="true" />
                        </button>
                        <button
                            type="button"
                            class="rounded-sm p-1 text-stone-400 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-500/10"
                            aria-label="Excluir avaliação"
                            @click="remove(review)"
                        >
                            <Trash2 class="size-4" aria-hidden="true" />
                        </button>
                    </div>
                </div>

                <p
                    class="mt-3 text-sm leading-relaxed whitespace-pre-line text-stone-700 dark:text-stone-300"
                >
                    {{ review.body }}
                </p>
            </li>
        </ul>

        <p
            v-else
            class="rounded-sm border border-dashed border-stone-300 px-6 py-16 text-center text-sm text-stone-500 dark:border-stone-700 dark:text-stone-400"
        >
            Nenhuma avaliação recebida ainda.
        </p>

        <p class="flex items-center gap-1.5 text-xs text-stone-400">
            <Star class="size-3.5" aria-hidden="true" />
            Avaliações entram como pendentes até a aprovação.
        </p>
    </div>
</template>
