<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import {
    fieldClasses,
    labelClasses,
    linkClasses,
    primaryButtonClasses,
} from '@/lib/formStyles';
import { login } from '@/routes';
import { store } from '@/routes/register';

defineOptions({
    layout: {
        title: 'Crie sua conta de administrador',
        description:
            'Leva menos de um minuto para começar a montar seu site com o CoreCMS',
    },
});

defineProps<{
    passwordRules: string;
}>();
</script>

<template>
    <Head title="Criar conta" />

    <Form
        v-bind="store.form()"
        :reset-on-success="['password', 'password_confirmation']"
        v-slot="{ errors, processing }"
        class="flex flex-col gap-6"
    >
        <div class="grid gap-5">
            <div class="grid gap-2">
                <Label for="name" :class="labelClasses"> Nome completo </Label>
                <Input
                    id="name"
                    type="text"
                    required
                    v-focus
                    :tabindex="1"
                    autocomplete="name"
                    name="name"
                    placeholder="Seu nome"
                    :class="fieldClasses"
                />
                <InputError :message="errors.name" />
            </div>

            <div class="grid gap-2">
                <Label for="email" :class="labelClasses"> E-mail </Label>
                <Input
                    id="email"
                    type="email"
                    required
                    :tabindex="2"
                    autocomplete="email"
                    name="email"
                    placeholder="voce@empresa.com"
                    :class="fieldClasses"
                />
                <InputError :message="errors.email" />
            </div>

            <div class="grid gap-2">
                <Label for="password" :class="labelClasses"> Senha </Label>
                <PasswordInput
                    id="password"
                    required
                    :tabindex="3"
                    autocomplete="new-password"
                    name="password"
                    placeholder="Crie uma senha forte"
                    :passwordrules="passwordRules"
                    :class="fieldClasses"
                />
                <InputError :message="errors.password" />
            </div>

            <div class="grid gap-2">
                <Label for="password_confirmation" :class="labelClasses">
                    Confirmar senha
                </Label>
                <PasswordInput
                    id="password_confirmation"
                    required
                    :tabindex="4"
                    autocomplete="new-password"
                    name="password_confirmation"
                    placeholder="Repita a senha"
                    :passwordrules="passwordRules"
                    :class="fieldClasses"
                />
                <InputError :message="errors.password_confirmation" />
            </div>

            <Button
                type="submit"
                class="mt-2"
                :class="primaryButtonClasses"
                :tabindex="5"
                :disabled="processing"
                :aria-busy="processing"
                data-test="register-user-button"
            >
                <Spinner v-if="processing" />
                {{ processing ? 'Criando conta...' : 'Criar conta' }}
            </Button>
        </div>

        <p class="text-center text-sm text-slate-600 dark:text-slate-400">
            Já possui uma conta?
            <Link :href="login()" :tabindex="6" :class="linkClasses">
                Entrar
            </Link>
        </p>
    </Form>
</template>
