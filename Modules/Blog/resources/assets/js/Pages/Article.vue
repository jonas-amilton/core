<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft } from '@lucide/vue';
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

const { locale, t } = useI18n();

const props = defineProps<{
    post: Post;
    navigation: NavigationItem[];
    latest: Post[];
}>();

const formatDate = (value: string | null): string =>
    value
        ? new Date(value).toLocaleDateString(locale.value.replace('_', '-'), {
              day: '2-digit',
              month: 'long',
              year: 'numeric',
          })
        : '';
</script>

<template>
    <Head :title="props.post.title" />

    <SiteShell :navigation="props.navigation">
        <article class="px-4 py-12 sm:px-6 sm:py-16">
            <Link
                href="/blog"
                class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-blue-700 dark:text-slate-400"
            >
                <ArrowLeft class="size-4" aria-hidden="true" />
                Blog
            </Link>

            <p class="mt-6 text-xs text-slate-400">
                {{ formatDate(props.post.published_at) }}
                <template v-if="props.post.category">
                    ·
                    <a
                        :href="`/blog/categoria/${props.post.category_slug}`"
                        class="hover:text-blue-700"
                    >
                        {{ props.post.category }}
                    </a>
                </template>
            </p>

            <h1
                class="mt-3 text-3xl leading-tight font-semibold tracking-tight text-slate-950 sm:text-4xl dark:text-slate-50"
            >
                {{ props.post.title }}
            </h1>

            <p
                v-if="props.post.excerpt"
                class="mt-3 text-lg text-slate-600 dark:text-slate-400"
            >
                {{ props.post.excerpt }}
            </p>

            <img
                v-if="props.post.cover"
                :src="props.post.cover"
                alt=""
                class="mt-8 aspect-video w-full rounded-lg border border-slate-200 object-cover dark:border-slate-800"
            />

            <div
                class="mt-8 max-w-2xl text-[17px] leading-[1.8] whitespace-pre-line text-slate-700 dark:text-slate-300"
            >
                {{ props.post.body }}
            </div>

            <section v-if="props.latest.length > 0" class="mt-14">
                <h2
                    class="text-xl font-semibold tracking-tight text-slate-950 dark:text-slate-50"
                >
                    {{ t('blog.readAlso') }}
                </h2>
                <ul class="mt-4 grid gap-3 sm:grid-cols-3">
                    <li
                        v-for="item in props.latest"
                        :key="item.id"
                        class="rounded-lg border border-slate-200 bg-white p-4 shadow-xs dark:border-slate-800 dark:bg-slate-900"
                    >
                        <a
                            :href="`/blog/${item.slug}`"
                            class="text-sm font-medium text-slate-900 hover:text-blue-700 dark:text-slate-100 dark:hover:text-blue-400"
                        >
                            {{ item.title }}
                        </a>
                    </li>
                </ul>
            </section>
        </article>
    </SiteShell>
</template>
