<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft, Check, ImageIcon, Save, Trash2 } from '@lucide/vue';
import { computed, ref } from 'vue';
import MediaPicker from '@modules/Media/resources/assets/js/components/MediaPicker.vue';
import type { PickedMedia } from '@modules/Media/resources/assets/js/types';
import { destroy, index as blogIndex, store, update } from '@/routes/blog';
import { index as categoriesIndex } from '@/routes/blog/category';
import { fieldClasses } from '@/lib/formStyles';

type Post = {
    id: number;
    title: string;
    slug: string;
    category_id: number | null;
    excerpt: string | null;
    body: string;
    cover: string | null;
    published_at: string | null;
    updated_at: string | null;
    translations: Record<
        'en' | 'es',
        { title: string | null; excerpt: string | null; body: string | null }
    >;
};

type Category = {
    id: number;
    name: string;
    slug: string;
};

const props = defineProps<{
    post: Post | null;
    categories: Category[];
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
    title: props.post?.title ?? '',
    slug: props.post?.slug ?? '',
    category_id: props.post?.category_id ?? null,
    excerpt: props.post?.excerpt ?? '',
    body: props.post?.body ?? '',
    cover: props.post?.cover ?? '',
    published:
        props.post?.published_at !== null &&
        props.post?.published_at !== undefined,
    translations: {
        en: {
            title: props.post?.translations?.en?.title ?? '',
            excerpt: props.post?.translations?.en?.excerpt ?? '',
            body: props.post?.translations?.en?.body ?? '',
        },
        es: {
            title: props.post?.translations?.es?.title ?? '',
            excerpt: props.post?.translations?.es?.excerpt ?? '',
            body: props.post?.translations?.es?.body ?? '',
        },
    },
});

function translatedField(field: 'title' | 'excerpt' | 'body') {
    return computed<string>({
        get: () =>
            editLocale.value === 'pt_BR'
                ? form[field]
                : (form.translations[editLocale.value as 'en' | 'es'][field] ??
                  ''),
        set: (value: string) => {
            if (editLocale.value === 'pt_BR') {
                form[field] = value;

                return;
            }

            form.translations[editLocale.value as 'en' | 'es'][field] = value;
        },
    });
}

const title = translatedField('title');
const excerpt = translatedField('excerpt');
const body = translatedField('body');

function save(): void {
    if (props.post) {
        form.patch(update.url(props.post.id), { preserveScroll: true });

        return;
    }

    form.post(store.url());
}

function remove(): void {
    if (!props.post || !window.confirm(`Excluir "${props.post.title}"?`)) {
        return;
    }

    form.delete(destroy.url(props.post.id));
}

function pickCover(media: PickedMedia): void {
    form.cover = media.url;
}
</script>

<template>
    <Head :title="props.post ? `Editar: ${props.post.title}` : 'Novo artigo'" />

    <div class="flex flex-1 flex-col gap-6 p-4 sm:p-6">
        <header class="flex flex-wrap items-center justify-between gap-3">
            <Link
                :href="blogIndex()"
                class="inline-flex items-center gap-1.5 rounded-md px-2 py-1 text-sm text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-100"
            >
                <ArrowLeft class="size-4" aria-hidden="true" />
                Blog
            </Link>
            <Link
                :href="categoriesIndex()"
                class="text-sm text-slate-500 hover:text-indigo-600 dark:text-slate-400"
            >
                Categorias
            </Link>
        </header>

        <form
            class="grid gap-4 lg:grid-cols-[1fr_320px]"
            @submit.prevent="save"
        >
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
                    <span
                        class="text-xs font-medium text-slate-600 dark:text-slate-400"
                    >
                        Título
                    </span>
                    <input
                        v-model="title"
                        type="text"
                        :class="fieldClasses"
                    />
                    <span v-if="form.errors.title" class="text-xs text-red-600">
                        {{ form.errors.title }}
                    </span>
                </label>

                <label class="grid gap-1.5">
                    <span
                        class="text-xs font-medium text-slate-600 dark:text-slate-400"
                    >
                        Resumo
                    </span>
                    <textarea
                        v-model="excerpt"
                        :rows="2"
                        :class="[fieldClasses, 'h-auto py-2 leading-relaxed']"
                    />
                </label>

                <label class="grid gap-1.5">
                    <span
                        class="text-xs font-medium text-slate-600 dark:text-slate-400"
                    >
                        Conteúdo
                    </span>
                    <textarea
                        v-model="body"
                        :rows="18"
                        :class="[fieldClasses, 'h-auto py-2 leading-relaxed']"
                    />
                    <span v-if="form.errors.body" class="text-xs text-red-600">
                        {{ form.errors.body }}
                    </span>
                </label>
            </div>

            <aside class="grid content-start gap-4">
                <div
                    class="grid gap-3 rounded-xl border border-slate-200 bg-white p-4 dark:border-slate-800 dark:bg-slate-900"
                >
                    <label class="grid gap-1.5">
                        <span
                            class="text-xs font-medium text-slate-600 dark:text-slate-400"
                        >
                            Categoria
                        </span>
                        <select
                            v-model="form.category_id"
                            :class="fieldClasses"
                        >
                            <option :value="null">Sem categoria</option>
                            <option
                                v-for="category in props.categories"
                                :key="category.id"
                                :value="category.id"
                            >
                                {{ category.name }}
                            </option>
                        </select>
                    </label>

                    <label class="grid gap-1.5">
                        <span
                            class="text-xs font-medium text-slate-600 dark:text-slate-400"
                        >
                            Endereço (slug)
                        </span>
                        <input
                            v-model="form.slug"
                            type="text"
                            placeholder="gerado pelo título"
                            :class="fieldClasses"
                        />
                    </label>

                    <label
                        class="flex items-center gap-2 text-sm text-slate-700 dark:text-slate-300"
                    >
                        <input v-model="form.published" type="checkbox" />
                        Publicado
                    </label>
                </div>

                <div
                    class="grid gap-2 rounded-xl border border-slate-200 bg-white p-4 dark:border-slate-800 dark:bg-slate-900"
                >
                    <span
                        class="text-xs font-medium text-slate-600 dark:text-slate-400"
                    >
                        Capa
                    </span>
                    <img
                        v-if="form.cover"
                        :src="form.cover"
                        alt=""
                        class="aspect-video w-full rounded-lg object-cover"
                    />
                    <button
                        type="button"
                        class="inline-flex h-9 items-center justify-center gap-1.5 rounded-lg border border-slate-200 px-3 text-sm font-medium text-slate-700 hover:border-indigo-400 hover:text-indigo-600 dark:border-slate-800 dark:text-slate-200"
                        @click="pickerOpen = true"
                    >
                        <ImageIcon class="size-4" aria-hidden="true" />
                        Escolher imagem
                    </button>
                </div>

                <div class="flex items-center gap-2">
                    <button
                        type="submit"
                        class="inline-flex h-10 items-center gap-2 rounded-lg bg-indigo-600 px-4 text-sm font-medium text-white hover:bg-indigo-500 disabled:opacity-50"
                        :disabled="form.processing"
                    >
                        <component
                            :is="props.post ? Save : Check"
                            class="size-4"
                            aria-hidden="true"
                        />
                        {{ props.post ? 'Salvar' : 'Criar artigo' }}
                    </button>

                    <button
                        v-if="props.post"
                        type="button"
                        class="inline-flex h-10 items-center gap-2 rounded-lg border border-slate-200 px-3 text-sm font-medium text-red-600 hover:bg-red-50 dark:border-slate-800 dark:hover:bg-red-500/10"
                        @click="remove"
                    >
                        <Trash2 class="size-4" aria-hidden="true" />
                        Excluir
                    </button>
                </div>

                <p
                    v-if="form.recentlySuccessful"
                    class="text-sm text-emerald-600"
                >
                    Salvo.
                </p>
            </aside>
        </form>

        <MediaPicker
            :open="pickerOpen"
            @close="pickerOpen = false"
            @pick="pickCover"
        />
    </div>
</template>
