<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import SiteShell from '@modules/Page/resources/assets/js/components/SiteShell.vue';
import type { NavigationItem } from '@modules/Page/resources/assets/js/types';
import { useI18n } from '@/i18n';

type Post = {
    id: number;
    title: string;
    slug: string;
    excerpt: string | null;
    body: string;
    cover: string | null;
    published_at: string | null;
    category: string | null;
    category_slug: string | null;
};

type Category = {
    id: number;
    name: string;
    slug: string;
};

const { locale, t } = useI18n();

const props = defineProps<{
    posts: Post[];
    categories: Category[];
    activeCategory: Category | null;
    navigation: NavigationItem[];
}>();

const formatDate = (value: string | null): string =>
    value
        ? new Date(value).toLocaleDateString(locale.value.replace('_', '-'), {
              day: '2-digit',
              month: 'long',
              year: 'numeric',
          })
        : '';

const title = props.activeCategory
    ? `Blog · ${props.activeCategory.name}`
    : t('blog.title');
</script>

<template>
    <Head :title="title" />

    <SiteShell :navigation="props.navigation">
        <section class="px-4 py-10 sm:px-6">
            <h1
                class="text-3xl font-semibold tracking-tight text-slate-900 dark:text-slate-100"
            >
                {{ props.activeCategory ? props.activeCategory.name : t('blog.title') }}
            </h1>

            <nav
                v-if="props.categories.length > 0"
                :aria-label="t('blog.categoriesAria')"
                class="mt-4 flex flex-wrap gap-2"
            >
                <a
                    href="/blog"
                    :class="[
                        'rounded-full border px-3 py-1 text-xs font-medium',
                        props.activeCategory === null
                            ? 'border-indigo-200 bg-indigo-50 text-indigo-700 dark:border-indigo-500/30 dark:bg-indigo-500/10 dark:text-indigo-300'
                            : 'border-slate-200 text-slate-600 hover:border-indigo-300 dark:border-slate-800 dark:text-slate-400',
                    ]"
                >
                    {{ t('blog.all') }}
                </a>
                <a
                    v-for="category in props.categories"
                    :key="category.id"
                    :href="`/blog/categoria/${category.slug}`"
                    :class="[
                        'rounded-full border px-3 py-1 text-xs font-medium',
                        props.activeCategory?.id === category.id
                            ? 'border-indigo-200 bg-indigo-50 text-indigo-700 dark:border-indigo-500/30 dark:bg-indigo-500/10 dark:text-indigo-300'
                            : 'border-slate-200 text-slate-600 hover:border-indigo-300 dark:border-slate-800 dark:text-slate-400',
                    ]"
                >
                    {{ category.name }}
                </a>
            </nav>

            <ul class="mt-8 grid gap-6 sm:grid-cols-2">
                <li
                    v-for="post in props.posts"
                    :key="post.id"
                    class="overflow-hidden rounded-xl border border-slate-200 transition-colors hover:border-indigo-300 dark:border-slate-800 dark:hover:border-indigo-500/40"
                >
                    <a :href="`/blog/${post.slug}`" class="block">
                        <img
                            v-if="post.cover"
                            :src="post.cover"
                            alt=""
                            class="aspect-video w-full object-cover"
                        />
                        <div class="p-5">
                            <p class="text-xs text-slate-400">
                                {{ formatDate(post.published_at) }}
                                <template v-if="post.category">
                                    · {{ post.category }}
                                </template>
                            </p>
                            <h2
                                class="mt-1 text-lg font-semibold text-slate-900 dark:text-slate-100"
                            >
                                {{ post.title }}
                            </h2>
                            <p
                                v-if="post.excerpt"
                                class="mt-2 line-clamp-3 text-sm text-slate-600 dark:text-slate-400"
                            >
                                {{ post.excerpt }}
                            </p>
                        </div>
                    </a>
                </li>
            </ul>

            <p
                v-if="props.posts.length === 0"
                class="mt-10 rounded-xl border border-dashed border-slate-300 px-6 py-16 text-center text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400"
            >
                {{ t('blog.empty') }}
            </p>
        </section>
    </SiteShell>
</template>
