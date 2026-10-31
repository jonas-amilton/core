<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import PasskeyVerify from '@/components/PasskeyVerify.vue';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { register } from '@/routes';
import { store } from '@/routes/login';
import { request } from '@/routes/password';

defineOptions({
    layout: {
        title: 'Acesse sua conta',
        description: 'Use seu e-mail e senha para entrar no painel do CoreCMS',
    },
});

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

const fieldClasses =
    'h-11 rounded-lg border-slate-200 bg-white px-3.5 text-sm shadow-sm placeholder:text-slate-400 hover:border-slate-300 focus-visible:border-indigo-500 focus-visible:ring-2 focus-visible:ring-indigo-500/30 dark:border-slate-800 dark:bg-slate-950/60 dark:placeholder:text-slate-500 dark:hover:border-slate-700';

const linkClasses =
    'rounded-sm font-medium text-indigo-600 underline-offset-4 transition-colors hover:underline focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:outline-none dark:text-indigo-400';
</script>

<template>
    <Head title="Entrar" />

    <div
        v-if="status"
        role="status"
        class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-center text-sm font-medium text-emerald-700 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-300"
    >
        {{ status }}
    </div>

    <PasskeyVerify
        label="Entrar com uma passkey"
        loading-label="Autenticando..."
        separator="ou continue com e-mail"
    />

    <Form
        v-bind="store.form()"
        :reset-on-success="['password']"
        v-slot="{ errors, processing }"
        class="flex flex-col gap-6"
    >
        <div class="grid gap-5">
            <div class="grid gap-2">
                <Label for="email" class="text-slate-700 dark:text-slate-300">
                    E-mail
                </Label>
                <Input
                    id="email"
                    type="email"
                    name="email"
                    required
                    v-focus
                    :tabindex="1"
                    autocomplete="email"
                    placeholder="voce@empresa.com"
                    :class="fieldClasses"
                />
                <InputError :message="errors.email" />
            </div>

            <div class="grid gap-2">
                <div class="flex items-center justify-between gap-3">
                    <Label
                        for="password"
                        class="text-slate-700 dark:text-slate-300"
                    >
                        Senha
                    </Label>
                    <Link
                        v-if="canResetPassword"
                        :href="request()"
                        :tabindex="5"
                        :class="[linkClasses, 'text-sm']"
                    >
                        Esqueci minha senha
                    </Link>
                </div>
                <PasswordInput
                    id="password"
                    name="password"
                    required
                    :tabindex="2"
                    autocomplete="current-password"
                    placeholder="Sua senha"
                    :class="fieldClasses"
                />
                <InputError :message="errors.password" />
            </div>

            <div class="flex items-center justify-between">
                <Label
                    for="remember"
                    class="flex items-center gap-2.5 text-sm font-normal text-slate-600 dark:text-slate-400"
                >
                    <Checkbox
                        id="remember"
                        name="remember"
                        :tabindex="3"
                        class="border-slate-300 data-[state=checked]:border-indigo-600 data-[state=checked]:bg-indigo-600 dark:border-slate-700"
                    />
                    <span>Lembrar de mim</span>
                </Label>
            </div>

            <Button
                type="submit"
                class="mt-2 h-11 w-full rounded-lg bg-indigo-600 font-medium text-white shadow-sm transition-colors hover:bg-indigo-500 disabled:opacity-70"
                :tabindex="4"
                :disabled="processing"
                :aria-busy="processing"
                data-test="login-button"
            >
                <Spinner v-if="processing" />
                {{ processing ? 'Entrando...' : 'Entrar' }}
            </Button>
        </div>

        <p class="text-center text-sm text-slate-600 dark:text-slate-400">
            Não tem uma conta?
            <Link :href="register()" :tabindex="5" :class="linkClasses">
                Cadastre-se
            </Link>
        </p>
    </Form>
</template>
