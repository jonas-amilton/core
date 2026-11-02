<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{
    title: string;
    description?: string | null;
    image?: string | null;
    type?: 'website' | 'article' | 'product';
}>();

const page = usePage();
const site = computed(
    () => (page.props.site as Record<string, string> | undefined) ?? {},
);
const canonical = computed(
    () => (page.props.canonical as string | undefined) ?? '',
);
const siteName = computed(() => site.value.site_name ?? '');

const summary = computed(() => {
    const text = props.description?.replace(/\s+/g, ' ').trim();

    if (!text) {
        return site.value.tagline ?? '';
    }

    return text.length > 160 ? `${text.slice(0, 157)}…` : text;
});
</script>

<template>
    <Head :title="title">
        <meta v-if="summary" name="description" :content="summary" />
        <meta property="og:title" :content="title" />
        <meta v-if="summary" property="og:description" :content="summary" />
        <meta property="og:type" :content="props.type ?? 'website'" />
        <meta v-if="siteName" property="og:site_name" :content="siteName" />
        <meta v-if="image" property="og:image" :content="image" />
        <meta v-if="canonical" property="og:url" :content="canonical" />
        <link v-if="canonical" rel="canonical" :href="canonical" />
    </Head>
</template>
