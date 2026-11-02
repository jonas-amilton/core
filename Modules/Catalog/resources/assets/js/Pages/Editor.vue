<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft, Check, ImageIcon, Save, Trash2, X } from '@lucide/vue';
import { computed, ref } from 'vue';
import MediaPicker from '@modules/Media/resources/assets/js/components/MediaPicker.vue';
import type { PickedMedia } from '@modules/Media/resources/assets/js/types';
import {
    destroy,
    index as productsIndex,
    store,
    update,
} from '@/routes/catalog/product';
import { index as categoryIndex } from '@/routes/catalog/category';
import { fieldClasses } from '@/lib/formStyles';

type ProductMedia = {
    id: number;
    url: string;
    alt: string | null;
    original_name: string;
};

type Product = {
    id: number;
    name: string;
    slug: string;
    sku: string | null;
    description: string | null;
    position: number;
    cover: string | null;
    media: ProductMedia[];
    category_ids: number[];
    related_ids: number[];
    translations: Record<'en' | 'es', { name: string | null; description: string | null }>;
};

type CategoryOption = {
    id: number;
    label: string;
};

type ProductOption = {
    id: number;
    name: string;
};

const props = defineProps<{
    product: Product | null;
    categories: CategoryOption[];
    related: ProductOption[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Produtos', href: productsIndex() }],
    },
});

const pickerOpen = ref(false);

type EditLocale = 'pt_BR' | 'en' | 'es';

const locales: EditLocale[] = ['pt_BR', 'en', 'es'];
const localeLabels: Record<EditLocale, string> = {
    pt_BR: 'PT',
    en: 'EN',
    es: 'ES',
};
const editLocale = ref<EditLocale>('pt_BR');

const form = useForm({
    name: props.product?.name ?? '',
    slug: props.product?.slug ?? '',
    sku: props.product?.sku ?? '',
    description: props.product?.description ?? '',
    position: props.product?.position ?? 0,
    category_ids: props.product?.category_ids ?? [],
    media_ids: props.product?.media.map((item) => item.id) ?? [],
    related_ids: props.product?.related_ids ?? [],
    translations: {
        en: {
            name: props.product?.translations.en.name ?? '',
            description: props.product?.translations.en.description ?? '',
        },
        es: {
            name: props.product?.translations.es.name ?? '',
            description: props.product?.translations.es.description ?? '',
        },
    },
});

const name = computed({
    get: () =>
        editLocale.value === 'pt_BR'
            ? form.name
            : (form.translations[editLocale.value].name ?? ''),
    set: (value: string) => {
        if (editLocale.value === 'pt_BR') {
            form.name = value;

            return;
        }

        form.translations[editLocale.value].name = value;
    },
});

const description = computed({
    get: () =>
        editLocale.value === 'pt_BR'
            ? form.description
            : (form.translations[editLocale.value].description ?? ''),
    set: (value: string) => {
        if (editLocale.value === 'pt_BR') {
            form.description = value;

            return;
        }

        form.translations[editLocale.value].description = value;
    },
});

const mediaItems = ref<ProductMedia[]>(props.product?.media ?? []);


function save(): void {
    if (props.product) {
        form.patch(update.url(props.product.id), { preserveScroll: true });

        return;
    }

    form.post(store.url());
}

function remove(): void {
    if (!props.product || !window.confirm(`Excluir "${props.product.name}"?`)) {
        return;
    }

    form.delete(destroy.url(props.product.id));
}

function addMedia(media: PickedMedia): void {
    if (!form.media_ids.includes(media.id)) {
        form.media_ids.push(media.id);
        mediaItems.value.push(media);
    }

    pickerOpen.value = false;
}

function removeMedia(id: number): void {
    form.media_ids = form.media_ids.filter((item) => item !== id);
    mediaItems.value = mediaItems.value.filter((item) => item.id !== id);
}
</script>

<template>
    <Head
        :title="props.product ? `Editar: ${props.product.name}` : 'Novo produto'"
    />

    <div class="flex flex-1 flex-col gap-6 p-4 sm:p-6">
        <header class="flex flex-wrap items-center justify-between gap-3">
            <Link
                :href="productsIndex()"
                class="inline-flex items-center gap-1.5 rounded-md px-2 py-1 text-sm text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-100"
            >
                <ArrowLeft class="size-4" aria-hidden="true" />
                Produtos
            </Link>
            <Link
                :href="categoryIndex()"
                class="text-sm text-slate-500 hover:text-indigo-600 dark:text-slate-400"
            >
                Categorias
            </Link>
        </header>

        <form class="grid gap-4 lg:grid-cols-[1fr_320px]" @submit.prevent="save">
            <div
                class="grid gap-4 rounded-xl border border-slate-200 bg-white p-4 dark:border-slate-800 dark:bg-slate-900"
            >
                <div class="flex items-center gap-1">
                    <button
                        v-for="loc in locales"
                        :key="loc"
                        type="button"
                        class="rounded-md px-2 py-0.5 text-[11px] font-medium transition-colors"
                        :class="
                            editLocale === loc
                                ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-300'
                                : 'text-slate-500 hover:bg-slate-100 dark:text-slate-500 dark:hover:bg-slate-800'
                        "
                        @click="editLocale = loc"
                    >
                        {{ localeLabels[loc] }}
                    </button>
                    <span
                        v-if="editLocale !== 'pt_BR'"
                        class="ml-1 text-[11px] text-slate-400"
                    >
                        Tradução — vazio usa o português
                    </span>
                </div>

                <label class="grid gap-1.5">
                    <span class="text-xs font-medium text-slate-600 dark:text-slate-400">
                        Nome
                    </span>
                    <input v-model="name" type="text" :class="fieldClasses" />
                    <span v-if="form.errors.name" class="text-xs text-red-600">
                        {{ form.errors.name }}
                    </span>
                </label>

                <label class="grid gap-1.5">
                    <span class="text-xs font-medium text-slate-600 dark:text-slate-400">
                        Descrição
                    </span>
                    <textarea
                        v-model="description"
                        :rows="16"
                        :class="[fieldClasses, 'h-auto py-2 leading-relaxed']"
                    />
                </label>

                <div
                    class="grid gap-2 rounded-lg border border-slate-200 p-3 dark:border-slate-800"
                >
                    <span class="text-xs font-medium text-slate-600 dark:text-slate-400">
                        Categorias
                    </span>
                    <label
                        v-for="option in props.categories"
                        :key="option.id"
                        class="flex items-center gap-2 text-sm text-slate-700 dark:text-slate-300"
                    >
                        <input
                            v-model="form.category_ids"
                            type="checkbox"
                            :value="option.id"
                        />
                        {{ option.label }}
                    </label>
                    <p
                        v-if="props.categories.length === 0"
                        class="text-xs text-slate-400"
                    >
                        Crie categorias primeiro.
                    </p>
                </div>
            </div>

            <aside class="grid content-start gap-4">
                <div
                    class="grid gap-3 rounded-xl border border-slate-200 bg-white p-4 dark:border-slate-800 dark:bg-slate-900"
                >
                    <label class="grid gap-1.5">
                        <span class="text-xs font-medium text-slate-600 dark:text-slate-400">
                            SKU / código
                        </span>
                        <input v-model="form.sku" type="text" :class="fieldClasses" />
                    </label>

                    <label class="grid gap-1.5">
                        <span class="text-xs font-medium text-slate-600 dark:text-slate-400">
                            Endereço (slug)
                        </span>
                        <input
                            v-model="form.slug"
                            type="text"
                            placeholder="gerado pelo nome"
                            :class="fieldClasses"
                        />
                    </label>

                    <label class="grid gap-1.5">
                        <span class="text-xs font-medium text-slate-600 dark:text-slate-400">
                            Ordem
                        </span>
                        <input
                            v-model.number="form.position"
                            type="number"
                            min="0"
                            :class="fieldClasses"
                        />
                    </label>
                </div>

                <div
                    class="grid gap-2 rounded-xl border border-slate-200 bg-white p-4 dark:border-slate-800 dark:bg-slate-900"
                >
                    <span class="text-xs font-medium text-slate-600 dark:text-slate-400">
                        Fotos
                    </span>
                    <ul v-if="mediaItems.length > 0" class="grid gap-2">
                        <li
                            v-for="item in mediaItems"
                            :key="item.id"
                            class="flex items-center gap-2 rounded-lg border border-slate-200 p-2 dark:border-slate-800"
                        >
                            <img
                                :src="item.url"
                                alt=""
                                class="size-10 rounded object-contain"
                            />
                            <span class="flex-1 truncate text-xs text-slate-500">
                                {{ item.original_name }}
                            </span>
                            <button
                                type="button"
                                class="rounded-md p-1 text-slate-400 hover:text-red-600"
                                aria-label="Remover foto"
                                @click="removeMedia(item.id)"
                            >
                                <X class="size-3.5" aria-hidden="true" />
                            </button>
                        </li>
                    </ul>
                    <button
                        type="button"
                        class="inline-flex h-9 items-center justify-center gap-1.5 rounded-lg border border-slate-200 px-3 text-sm font-medium text-slate-700 hover:border-indigo-400 hover:text-indigo-600 dark:border-slate-800 dark:text-slate-200"
                        @click="pickerOpen = true"
                    >
                        <ImageIcon class="size-4" aria-hidden="true" />
                        Adicionar foto
                    </button>
                    <p class="text-[11px] text-slate-400">
                        A primeira foto é a capa.
                    </p>
                </div>

                <div
                    class="grid gap-2 rounded-xl border border-slate-200 bg-white p-4 dark:border-slate-800 dark:bg-slate-900"
                >
                    <label class="grid gap-1.5">
                        <span class="text-xs font-medium text-slate-600 dark:text-slate-400">
                            Produtos relacionados
                        </span>
                        <select
                            v-model="form.related_ids"
                            multiple
                            :size="Math.min(8, Math.max(3, props.related.length))"
                            :class="fieldClasses"
                        >
                            <option
                                v-for="option in props.related"
                                :key="option.id"
                                :value="option.id"
                                :disabled="option.id === props.product?.id"
                            >
                                {{ option.name }}
                            </option>
                        </select>
                    </label>
                </div>

                <div class="flex items-center gap-2">
                    <button
                        type="submit"
                        class="inline-flex h-10 items-center gap-2 rounded-lg bg-indigo-600 px-4 text-sm font-medium text-white hover:bg-indigo-500 disabled:opacity-50"
                        :disabled="form.processing"
                    >
                        <component
                            :is="props.product ? Save : Check"
                            class="size-4"
                            aria-hidden="true"
                        />
                        {{ props.product ? 'Salvar' : 'Criar produto' }}
                    </button>

                    <button
                        v-if="props.product"
                        type="button"
                        class="inline-flex h-10 items-center gap-2 rounded-lg border border-slate-200 px-3 text-sm font-medium text-red-600 hover:bg-red-50 dark:border-slate-800 dark:hover:bg-red-500/10"
                        @click="remove"
                    >
                        <Trash2 class="size-4" aria-hidden="true" />
                        Excluir
                    </button>
                </div>

                <p v-if="form.recentlySuccessful" class="text-sm text-emerald-600">
                    Salvo.
                </p>
            </aside>
        </form>

        <MediaPicker
            :open="pickerOpen"
            @close="pickerOpen = false"
            @pick="addMedia"
        />
    </div>
</template>
