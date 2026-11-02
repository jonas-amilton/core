<script setup lang="ts">
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { Check, Trash2 } from '@lucide/vue';
import { computed } from 'vue';
import SiteShell from '@modules/Page/resources/assets/js/components/SiteShell.vue';
import type { NavigationItem } from '@modules/Page/resources/assets/js/types';
import { destroy } from '@/routes/quote/item';
import { store } from '@/routes/quote';
import { fieldClasses } from '@/lib/formStyles';

type CartItem = {
    id: number;
    name: string;
    slug: string;
    sku: string | null;
    cover: string | null;
    quantity: number;
};

const props = defineProps<{
    items: CartItem[];
    navigation: NavigationItem[];
}>();

const cartError = computed(
    () => (form.errors as Record<string, string | undefined>).cart,
);

const form = useForm({
    name: '',
    email: '',
    phone: '',
    message: '',
    website: '',
});

function remove(item: CartItem): void {
    router.delete(destroy.url(item.id), { preserveScroll: true });
}

function submit(): void {
    form.post(store.url(), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
}
</script>

<template>
    <Head title="Orçamento" />

    <SiteShell :navigation="props.navigation">
        <section class="px-4 py-10 sm:px-6">
            <h1
                class="text-3xl font-semibold tracking-tight text-slate-900 dark:text-slate-100"
            >
                Orçamento
            </h1>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
                Monte sua lista de produtos e envie. Respondemos por e-mail.
            </p>

            <p
                v-if="form.recentlySuccessful"
                class="mt-4 inline-flex items-center gap-1.5 rounded-lg bg-emerald-50 px-3 py-2 text-sm text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300"
            >
                <Check class="size-4" aria-hidden="true" />
                Orçamento enviado. Entraremos em contato em breve.
            </p>

            <p
                v-if="cartError"
                class="mt-4 rounded-lg bg-red-50 px-3 py-2 text-sm text-red-700 dark:bg-red-500/10 dark:text-red-300"
            >
                {{ cartError }}
            </p>

            <div v-if="props.items.length > 0" class="mt-6 grid gap-6 lg:grid-cols-2">
                <ul class="grid content-start gap-2">
                    <li
                        v-for="item in props.items"
                        :key="item.id"
                        class="flex items-center gap-3 rounded-xl border border-slate-200 p-3 dark:border-slate-800"
                    >
                        <img
                            v-if="item.cover"
                            :src="item.cover"
                            alt=""
                            class="size-16 rounded-lg bg-white object-contain p-1"
                        />
                        <div class="min-w-0 flex-1">
                            <Link
                                :href="`/produto/${item.slug}`"
                                class="text-sm font-medium text-slate-900 hover:text-indigo-600 dark:text-slate-100 dark:hover:text-indigo-400"
                            >
                                {{ item.name }}
                            </Link>
                            <p class="text-xs text-slate-500">
                                <template v-if="item.sku">SKU {{ item.sku }} · </template>
                                Qtd. {{ item.quantity }}
                            </p>
                        </div>
                        <button
                            type="button"
                            class="rounded-md p-1 text-slate-400 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-500/10"
                            :aria-label="`Remover ${item.name}`"
                            @click="remove(item)"
                        >
                            <Trash2 class="size-4" aria-hidden="true" />
                        </button>
                    </li>
                </ul>

                <form
                    class="grid content-start gap-3 rounded-xl border border-slate-200 p-4 dark:border-slate-800"
                    @submit.prevent="submit"
                >
                    <label class="grid gap-1.5">
                        <span class="text-xs font-medium text-slate-600 dark:text-slate-400">
                            Nome
                        </span>
                        <input v-model="form.name" type="text" :class="fieldClasses" />
                        <span v-if="form.errors.name" class="text-xs text-red-600">
                            {{ form.errors.name }}
                        </span>
                    </label>

                    <label class="grid gap-1.5">
                        <span class="text-xs font-medium text-slate-600 dark:text-slate-400">
                            E-mail
                        </span>
                        <input v-model="form.email" type="email" :class="fieldClasses" />
                        <span v-if="form.errors.email" class="text-xs text-red-600">
                            {{ form.errors.email }}
                        </span>
                    </label>

                    <label class="grid gap-1.5">
                        <span class="text-xs font-medium text-slate-600 dark:text-slate-400">
                            Telefone (opcional)
                        </span>
                        <input v-model="form.phone" type="tel" :class="fieldClasses" />
                    </label>

                    <label class="grid gap-1.5">
                        <span class="text-xs font-medium text-slate-600 dark:text-slate-400">
                            Observações
                        </span>
                        <textarea
                            v-model="form.message"
                            :rows="4"
                            :class="[fieldClasses, 'h-auto py-2 leading-relaxed']"
                        />
                    </label>

                    <input
                        v-model="form.website"
                        type="text"
                        name="website"
                        tabindex="-1"
                        autocomplete="off"
                        class="hidden"
                        aria-hidden="true"
                    />

                    <button
                        type="submit"
                        class="inline-flex h-10 items-center justify-center rounded-lg bg-indigo-600 px-5 text-sm font-medium text-white hover:bg-indigo-500 disabled:opacity-50"
                        :disabled="form.processing"
                    >
                        Enviar orçamento
                    </button>
                </form>
            </div>

            <p
                v-else
                class="mt-8 rounded-xl border border-dashed border-slate-300 px-6 py-16 text-center text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400"
            >
                Seu orçamento está vazio.
                <Link href="/produtos" class="text-indigo-600 hover:underline dark:text-indigo-400">
                    Ver produtos
                </Link>
            </p>
        </section>
    </SiteShell>
</template>
