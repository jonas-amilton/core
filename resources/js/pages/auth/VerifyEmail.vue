<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import {
    linkClasses,
    primaryButtonClasses,
    statusClasses,
} from '@/lib/formStyles';
import { logout } from '@/routes';

defineOptions({
    layout: {
        title: 'Confirme seu e-mail',
        description:
            'Clique no link que enviamos para o seu e-mail para ativar a conta',
    },
});

defineProps<{
    status?: string;
}>();
</script>

<template>
    <Head title="Verificação de e-mail" />

    <div
        v-if="status === 'verification-link-sent'"
        role="status"
        :class="statusClasses"
    >
        Um novo link de verificação foi enviado para o e-mail informado no
        cadastro.
    </div>

    <!-- shortcut: POST literal porque a feature de verificação do Fortify está desligada (sem rota wayfinder), reative Features::emailVerification() e volte a usar @/routes/verification -->
    <Form
        action="/email/verification-notification"
        method="post"
        class="space-y-6 text-center"
        v-slot="{ processing }"
    >
        <Button
            :disabled="processing"
            :aria-busy="processing"
            :class="primaryButtonClasses"
        >
            <Spinner v-if="processing" />
            {{ processing ? 'Enviando...' : 'Reenviar e-mail de verificação' }}
        </Button>

        <Link :href="logout()" as="button" :class="[linkClasses, 'text-sm']">
            Sair da conta
        </Link>
    </Form>
</template>
