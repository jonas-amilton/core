<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import {
    fieldClasses,
    labelClasses,
    linkClasses,
    primaryButtonClasses,
    statusClasses,
} from '@/lib/formStyles';
import { login } from '@/routes';
import { email } from '@/routes/password';

defineOptions({
    layout: {
        title: 'Recupere sua senha',
        description:
            'Informe seu e-mail e enviaremos um link para criar uma nova senha',
    },
});

defineProps<{
    status?: string;
}>();
</script>

<template>
    <Head title="Recuperar senha" />

    <div v-if="status" role="status" :class="statusClasses">
        {{ status }}
    </div>

    <div class="space-y-6">
        <Form v-bind="email.form()" v-slot="{ errors, processing }">
            <div class="grid gap-2">
                <Label for="email" :class="labelClasses">E-mail</Label>
                <Input
                    id="email"
                    type="email"
                    name="email"
                    autocomplete="off"
                    v-focus
                    placeholder="voce@empresa.com"
                    :class="fieldClasses"
                />
                <InputError :message="errors.email" />
            </div>

            <div class="my-6 flex items-center justify-start">
                <Button
                    :class="primaryButtonClasses"
                    :disabled="processing"
                    :aria-busy="processing"
                    data-test="email-password-reset-link-button"
                >
                    <Spinner v-if="processing" />
                    {{
                        processing
                            ? 'Enviando...'
                            : 'Enviar link de redefinição'
                    }}
                </Button>
            </div>
        </Form>

        <p class="text-center text-sm text-slate-600 dark:text-slate-400">
            Ou volte para
            <Link :href="login()" :class="linkClasses">entrar</Link>
        </p>
    </div>
</template>
