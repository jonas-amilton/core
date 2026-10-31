<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import SecurityController from '@/actions/App/Http/Controllers/Settings/SecurityController';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import {
    cardClasses,
    fieldClasses,
    labelClasses,
    primaryButtonClasses,
} from '@/lib/formStyles';
import { edit } from '@/routes/security';
import type { Props as ManagePasskeysProps } from '@/components/ManagePasskeys.vue';
import ManagePasskeys from '@/components/ManagePasskeys.vue';
import type { Props as ManageTwoFactorProps } from '@/components/ManageTwoFactor.vue';
import ManageTwoFactor from '@/components/ManageTwoFactor.vue';

// oxfmt-ignore
type Props = {
    passwordRules: string;
} & ManagePasskeysProps &
    ManageTwoFactorProps;

const props = defineProps<Props>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Segurança',
                href: edit(),
            },
        ],
    },
});
</script>

<template>
    <Head title="Segurança" />

    <h1 class="sr-only">Configurações de segurança</h1>

    <div class="space-y-8">
        <section :class="cardClasses">
            <Heading
                variant="small"
                title="Alterar senha"
                description="Use uma senha longa e aleatória para manter sua conta protegida"
            />

            <Form
                v-bind="SecurityController.update.form()"
                :options="{
                    preserveScroll: true,
                }"
                reset-on-success
                :reset-on-error="[
                    'password',
                    'password_confirmation',
                    'current_password',
                ]"
                class="mt-6 space-y-5"
                v-slot="{ errors, processing }"
            >
                <div class="grid gap-2">
                    <Label for="current_password" :class="labelClasses">
                        Senha atual
                    </Label>
                    <PasswordInput
                        id="current_password"
                        name="current_password"
                        autocomplete="current-password"
                        placeholder="Senha atual"
                        :class="fieldClasses"
                    />
                    <InputError :message="errors.current_password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password" :class="labelClasses">
                        Nova senha
                    </Label>
                    <PasswordInput
                        id="password"
                        name="password"
                        autocomplete="new-password"
                        placeholder="Nova senha"
                        :passwordrules="props.passwordRules"
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
                        placeholder="Repita a nova senha"
                        :passwordrules="props.passwordRules"
                        :class="fieldClasses"
                    />
                    <InputError :message="errors.password_confirmation" />
                </div>

                <div class="flex items-center gap-4 pt-1">
                    <Button
                        :disabled="processing"
                        :aria-busy="processing"
                        :class="[primaryButtonClasses, 'w-auto px-6']"
                        data-test="update-password-button"
                    >
                        {{ processing ? 'Salvando...' : 'Salvar' }}
                    </Button>
                </div>
            </Form>
        </section>

        <ManageTwoFactor
            :canManageTwoFactor="canManageTwoFactor"
            :requiresConfirmation="requiresConfirmation"
            :twoFactorEnabled="twoFactorEnabled"
        />

        <ManagePasskeys
            :canManagePasskeys="canManagePasskeys"
            :passkeys="passkeys"
        />
    </div>
</template>
