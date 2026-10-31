<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import PasskeyVerify from '@/components/PasskeyVerify.vue';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import {
    fieldClasses,
    labelClasses,
    primaryButtonClasses,
} from '@/lib/formStyles';
import { store } from '@/routes/password/confirm';
import {
    index as confirmOptions,
    store as confirmStore,
} from '@/actions/Laravel/Passkeys/Http/Controllers/PasskeyConfirmationController';

defineOptions({
    layout: {
        title: 'Confirme sua senha',
        description:
            'Esta é uma área protegida. Confirme sua senha para continuar.',
    },
});
</script>

<template>
    <Head title="Confirmar senha" />

    <PasskeyVerify
        :routes="{
            options: confirmOptions(),
            submit: confirmStore(),
        }"
        label="Confirmar com passkey"
        loading-label="Confirmando..."
        separator="ou confirme com a senha"
    />

    <Form
        v-bind="store.form()"
        reset-on-success
        v-slot="{ errors, processing }"
    >
        <div class="space-y-5">
            <div class="grid gap-2">
                <Label for="password" :class="labelClasses">Senha</Label>
                <PasswordInput
                    id="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    autofocus
                    placeholder="Sua senha"
                    :class="fieldClasses"
                />

                <InputError :message="errors.password" />
            </div>

            <div class="flex items-center">
                <Button
                    :class="primaryButtonClasses"
                    :disabled="processing"
                    :aria-busy="processing"
                    data-test="confirm-password-button"
                >
                    <Spinner v-if="processing" />
                    {{ processing ? 'Confirmando...' : 'Confirmar senha' }}
                </Button>
            </div>
        </div>
    </Form>
</template>
