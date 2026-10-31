<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppearanceTabs from '@/components/AppearanceTabs.vue';
import Heading from '@/components/Heading.vue';
import { cardClasses } from '@/lib/formStyles';
import { edit } from '@/routes/appearance';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Aparência',
                href: edit(),
            },
        ],
    },
});
</script>

<template>
    <Head title="Aparência" />

    <h1 class="sr-only">Configurações de aparência</h1>

    <section :class="cardClasses">
        <Heading
            variant="small"
            title="Aparência"
            description="Escolha como o CoreCMS deve ser exibido para você"
        />
        <div class="mt-6">
            <AppearanceTabs />
        </div>
    </section>
</template>
