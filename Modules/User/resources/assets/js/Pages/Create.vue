<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft, UserPlus } from '@lucide/vue';
import {
    fieldClasses,
    labelClasses,
    primaryButtonClasses,
} from '@/lib/formStyles';
import { index as userIndex, store } from '@/routes/user';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Usuários',
                href: userIndex(),
            },
        ],
    },
});

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

function submit(): void {
    form.post(store().url, {
        preserveScroll: true,
        onSuccess: () => form.reset('password', 'password_confirmation'),
    });
}
</script>

<template>
    <Head title="Novo usuário" />

    <div class="flex flex-1 flex-col gap-6 p-4 sm:p-6">
        <header>
            <Link
                :href="userIndex()"
                class="inline-flex items-center gap-1.5 text-sm text-stone-600 hover:text-stone-900 dark:text-stone-400 dark:hover:text-stone-100"
            >
                <ArrowLeft class="size-4" aria-hidden="true" />
                Usuários
            </Link>
            <h1
                class="mt-2 text-2xl font-semibold tracking-tight text-stone-900 dark:text-stone-100"
            >
                Novo usuário
            </h1>
            <p class="mt-1 text-sm text-stone-600 dark:text-stone-400">
                A conta já é criada com acesso total ao painel.
            </p>
        </header>

        <form class="max-w-xl space-y-5" @submit.prevent="submit">
            <div class="grid gap-2">
                <label for="name" :class="labelClasses">Nome</label>
                <input
                    id="name"
                    v-model="form.name"
                    type="text"
                    required
                    autocomplete="name"
                    :class="fieldClasses"
                />
                <p
                    v-if="form.errors.name"
                    class="text-sm text-red-600 dark:text-red-400"
                >
                    {{ form.errors.name }}
                </p>
            </div>

            <div class="grid gap-2">
                <label for="email" :class="labelClasses">E-mail</label>
                <input
                    id="email"
                    v-model="form.email"
                    type="email"
                    required
                    autocomplete="email"
                    :class="fieldClasses"
                />
                <p
                    v-if="form.errors.email"
                    class="text-sm text-red-600 dark:text-red-400"
                >
                    {{ form.errors.email }}
                </p>
            </div>

            <div class="grid gap-2">
                <label for="password" :class="labelClasses">Senha</label>
                <input
                    id="password"
                    v-model="form.password"
                    type="password"
                    required
                    autocomplete="new-password"
                    :class="fieldClasses"
                />
                <p
                    v-if="form.errors.password"
                    class="text-sm text-red-600 dark:text-red-400"
                >
                    {{ form.errors.password }}
                </p>
            </div>

            <div class="grid gap-2">
                <label for="password_confirmation" :class="labelClasses">
                    Confirmar senha
                </label>
                <input
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    required
                    autocomplete="new-password"
                    :class="fieldClasses"
                />
            </div>

            <button
                type="submit"
                :class="primaryButtonClasses"
                :disabled="form.processing"
                class="w-auto px-6"
            >
                <UserPlus class="size-4" aria-hidden="true" />
                {{ form.processing ? 'Criando...' : 'Criar usuário' }}
            </button>
        </form>
    </div>
</template>
