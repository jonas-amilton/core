<script setup lang="ts">
import { ArrowRight } from '@lucide/vue';
import SiteShell from '@modules/Page/resources/assets/js/components/SiteShell.vue';
import type { NavigationItem } from '@modules/Page/resources/assets/js/types';
import { useI18n } from '@/i18n';
import SiteHead from '@/components/SiteHead.vue';

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
    <SiteHead :title="title" />

    <SiteShell :navigation="props.navigation">
        <section class="px-4 py-12 sm:px-6 sm:py-16">
            <h1
                class="text-3xl font-semibold tracking-tight text-slate-950 sm:text-4xl dark:text-slate-50"
            >
                {{
                    props.activeCategory
                        ? props.activeCategory.name
                        : t('blog.title')
                }}
            </h1>

            <nav
                v-if="props.categories.length > 0"
                :aria-label="t('blog.categoriesAria')"
                class="mt-4 flex flex-wrap gap-2"
            >
                <a
                    href="/blog"
                    :class="[
                        'inline-flex h-9 items-center rounded-full px-4 text-sm font-medium transition-colors',
                        props.activeCategory === null
                            ? 'bg-brand text-white'
                            : 'bg-[#f5f5f5] text-slate-700 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300',
                    ]"
                >
                    {{ t('blog.all') }}
                </a>
                <a
                    v-for="category in props.categories"
                    :key="category.id"
                    :href="`/blog/categoria/${category.slug}`"
                    :class="[
                        'inline-flex h-9 items-center rounded-full px-4 text-sm font-medium transition-colors',
                        props.activeCategory?.id === category.id
                            ? 'bg-brand text-white'
                            : 'bg-[#f5f5f5] text-slate-700 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300',
                    ]"
                >
                    {{ category.name }}
                </a>
            </nav>

            <ul class="mt-8 grid gap-6 sm:grid-cols-2">
                <li
                    v-for="post in props.posts"
                    :key="post.id"
                    class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-xs transition-colors hover:border-green-300 dark:border-slate-800 dark:bg-slate-900 dark:hover:border-brand/40"
                >
                    <a :href="`/blog/${post.slug}`" class="group block">
                        <img
                            v-if="post.cover"
                            :src="post.cover"
                            alt=""
                            class="aspect-video w-full object-cover"
                        />
                        <div class="p-5">
                            <p class="text-[11px] font-medium tracking-[0.1em] text-slate-600 uppercase dark:text-slate-400">
                                {{ formatDate(post.published_at) }}
                                <template v-if="post.category">
                                    · {{ post.category }}
                                </template>
                            </p>
                            <h2
                                class="mt-2 text-base font-semibold text-slate-950 group-hover:text-brand dark:text-slate-50 dark:group-hover:text-green-400"
                            >
                                {{ post.title }}
                            </h2>
                            <p
                                v-if="post.excerpt"
                                class="mt-2 line-clamp-3 text-sm leading-relaxed text-slate-700 dark:text-slate-400"
                            >
                                {{ post.excerpt }}
                            </p>
                            <span
                                class="mt-4 inline-flex items-center gap-1.5 text-xs font-medium text-brand dark:text-green-400"
                            >
                                {{ t('blog.read') }}
                                <ArrowRight class="size-3.5" aria-hidden="true" />
                            </span>
                        </div>
                    </a>
                </li>
            </ul>

            <p
                v-if="props.posts.length === 0"
                class="mt-10 rounded-lg border border-dashed border-slate-300 bg-white px-6 py-16 text-center text-sm text-slate-600 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-400"
            >
                {{ t('blog.empty') }}
            </p>
        </section>
    </SiteShell>
</template>
