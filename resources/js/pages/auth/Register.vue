<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
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

const fieldClasses =
    'h-11 rounded-lg border-slate-200 bg-white px-3.5 text-sm shadow-sm placeholder:text-slate-400 hover:border-slate-300 focus-visible:border-indigo-500 focus-visible:ring-2 focus-visible:ring-indigo-500/30 dark:border-slate-800 dark:bg-slate-950/60 dark:placeholder:text-slate-500 dark:hover:border-slate-700';

const linkClasses =
    'rounded-sm font-medium text-indigo-600 underline-offset-4 transition-colors hover:underline focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:outline-none dark:text-indigo-400';
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
                <Label for="name" class="text-slate-700 dark:text-slate-300">
                    Nome completo
                </Label>
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
                <Label for="email" class="text-slate-700 dark:text-slate-300">
                    E-mail
                </Label>
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
                <Label
                    for="password"
                    class="text-slate-700 dark:text-slate-300"
                >
                    Senha
                </Label>
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
                <Label
                    for="password_confirmation"
                    class="text-slate-700 dark:text-slate-300"
                >
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
                class="mt-2 h-11 w-full rounded-lg bg-indigo-600 font-medium text-white shadow-sm transition-colors hover:bg-indigo-500 disabled:opacity-70"
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
