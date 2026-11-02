<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import {
    fieldClasses,
    labelClasses,
    primaryButtonClasses,
} from '@/lib/formStyles';
import { update } from '@/routes/password';

defineOptions({
    layout: {
        title: 'Crie uma nova senha',
        description: 'Escolha uma senha forte para voltar a acessar sua conta',
    },
});

const props = defineProps<{
    token: string;
    email: string;
    passwordRules: string;
}>();

const inputEmail = ref(props.email);
</script>

<template>
    <Head title="Redefinir senha" />

    <Form
        v-bind="update.form()"
        :transform="(data) => ({ ...data, token, email })"
        :reset-on-success="['password', 'password_confirmation']"
        v-slot="{ errors, processing }"
    >
        <div class="grid gap-5">
            <div class="grid gap-2">
                <Label for="email" :class="labelClasses">E-mail</Label>
                <Input
                    id="email"
                    type="email"
                    name="email"
                    autocomplete="email"
                    v-model="inputEmail"
                    readonly
                    :class="[
                        fieldClasses,
                        'cursor-not-allowed text-stone-500 dark:text-stone-400',
                    ]"
                />
                <InputError :message="errors.email" />
            </div>

            <div class="grid gap-2">
                <Label for="password" :class="labelClasses">Nova senha</Label>
                <PasswordInput
                    id="password"
                    name="password"
                    autocomplete="new-password"
                    autofocus
                    placeholder="Nova senha"
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
                    name="password_confirmation"
                    autocomplete="new-password"
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
                :disabled="processing"
                :aria-busy="processing"
                data-test="reset-password-button"
            >
                <Spinner v-if="processing" />
                {{ processing ? 'Salvando...' : 'Redefinir senha' }}
            </Button>
        </div>
    </Form>
</template>
