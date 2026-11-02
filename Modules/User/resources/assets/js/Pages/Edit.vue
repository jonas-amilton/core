<script setup lang="ts">
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ArrowLeft, Trash2 } from '@lucide/vue';
import type { UserItem } from '../types';
import {
    fieldClasses,
    labelClasses,
    primaryButtonClasses,
} from '@/lib/formStyles';
import { destroy, index as userIndex, update } from '@/routes/user';

const props = defineProps<{
    user: UserItem;
    isSelf: boolean;
}>();

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
    name: props.user.name,
    email: props.user.email,
    role: props.user.role,
    password: '',
    password_confirmation: '',
});

function submit(): void {
    form.patch(update.url(props.user.id), {
        preserveScroll: true,
        onSuccess: () => form.reset('password', 'password_confirmation'),
    });
}

function remove(): void {
    if (
        !window.confirm(
            `Excluir "${props.user.name}"? O acesso dessa pessoa ao painel será removido.`,
        )
    ) {
        return;
    }

    router.delete(destroy.url(props.user.id));
}
</script>

<template>
    <Head :title="`Editar ${user.name}`" />

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
                {{ user.name }}
            </h1>
            <p class="mt-1 text-sm text-stone-600 dark:text-stone-400">
                {{
                    isSelf
                        ? 'Esta é a sua conta.'
                        : 'Edite os dados de acesso desta pessoa.'
                }}
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
                <label for="role" :class="labelClasses">Perfil</label>
                <select id="role" v-model="form.role" :class="fieldClasses">
                    <option value="editor">Editor</option>
                    <option value="admin">Administrador</option>
                    <option value="customer">Cliente</option>
                </select>
                <p
                    v-if="form.errors.role"
                    class="text-sm text-red-600 dark:text-red-400"
                >
                    {{ form.errors.role }}
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
                <label for="password" :class="labelClasses">
                    Nova senha <span class="text-stone-400">(opcional)</span>
                </label>
                <input
                    id="password"
                    v-model="form.password"
                    type="password"
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
                    Confirmar nova senha
                </label>
                <input
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    autocomplete="new-password"
                    :class="fieldClasses"
                />
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <button
                    type="submit"
                    :class="[primaryButtonClasses, 'w-auto px-6']"
                    :disabled="form.processing"
                >
                    {{ form.processing ? 'Salvando...' : 'Salvar' }}
                </button>

                <button
                    v-if="!isSelf"
                    type="button"
                    class="inline-flex h-10 items-center gap-2 rounded-sm border border-red-200 px-4 text-sm font-medium text-red-600 transition-colors hover:bg-red-50 dark:border-red-500/30 dark:text-red-400 dark:hover:bg-red-500/10"
                    @click="remove"
                >
                    <Trash2 class="size-4" aria-hidden="true" />
                    Excluir usuário
                </button>
            </div>
        </form>
    </div>
</template>
