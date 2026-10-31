<script setup lang="ts">
import { Form, Head, setLayoutProps } from '@inertiajs/vue3';
import { computed, ref, watchEffect } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import {
    InputOTP,
    InputOTPGroup,
    InputOTPSlot,
} from '@/components/ui/input-otp';
import {
    fieldClasses,
    linkClasses,
    primaryButtonClasses,
} from '@/lib/formStyles';
import { store } from '@/routes/two-factor/login';
import type { TwoFactorConfigContent } from '@/types';

const showRecoveryInput = ref<boolean>(false);
const code = ref<string>('');

const authConfigContent = computed<TwoFactorConfigContent>(() => {
    if (showRecoveryInput.value) {
        return {
            title: 'Código de recuperação',
            description:
                'Confirme o acesso à sua conta com um dos seus códigos de emergência.',
            buttonText: 'entrar com o código do autenticador',
        };
    }

    return {
        title: 'Código de autenticação',
        description:
            'Digite o código de 6 dígitos gerado pelo seu aplicativo autenticador.',
        buttonText: 'entrar com um código de recuperação',
    };
});

watchEffect(() => {
    setLayoutProps({
        title: authConfigContent.value.title,
        description: authConfigContent.value.description,
    });
});

const toggleRecoveryMode = (clearErrors: () => void): void => {
    showRecoveryInput.value = !showRecoveryInput.value;
    clearErrors();
    code.value = '';
};
</script>

<template>
    <Head title="Autenticação em dois fatores" />

    <div class="space-y-6">
        <template v-if="!showRecoveryInput">
            <Form
                v-bind="store.form()"
                class="space-y-5"
                reset-on-error
                @error="code = ''"
                #default="{ errors, processing, clearErrors }"
            >
                <input type="hidden" name="code" :value="code" />
                <div
                    class="flex flex-col items-center justify-center space-y-3 text-center"
                >
                    <div class="flex w-full items-center justify-center">
                        <InputOTP
                            id="otp"
                            v-model="code"
                            :maxlength="6"
                            :disabled="processing"
                            autofocus
                        >
                            <InputOTPGroup class="gap-1">
                                <InputOTPSlot
                                    v-for="index in 6"
                                    :key="index"
                                    :index="index - 1"
                                    class="size-12 rounded-md border text-base dark:border-slate-800 dark:bg-slate-950/60"
                                />
                            </InputOTPGroup>
                        </InputOTP>
                    </div>
                    <InputError :message="errors.code" />
                </div>
                <Button
                    type="submit"
                    :class="primaryButtonClasses"
                    :disabled="processing"
                >
                    Continuar
                </Button>
                <div
                    class="text-center text-sm text-slate-600 dark:text-slate-400"
                >
                    <span>ou você pode </span>
                    <button
                        type="button"
                        :class="linkClasses"
                        @click="() => toggleRecoveryMode(clearErrors)"
                    >
                        {{ authConfigContent.buttonText }}
                    </button>
                </div>
            </Form>
        </template>

        <template v-else>
            <Form
                v-bind="store.form()"
                class="space-y-5"
                reset-on-error
                #default="{ errors, processing, clearErrors }"
            >
                <Input
                    name="recovery_code"
                    type="text"
                    placeholder="Código de recuperação"
                    v-focus
                    required
                    :class="fieldClasses"
                />
                <InputError :message="errors.recovery_code" />
                <Button
                    type="submit"
                    :class="primaryButtonClasses"
                    :disabled="processing"
                >
                    Continuar
                </Button>

                <div
                    class="text-center text-sm text-slate-600 dark:text-slate-400"
                >
                    <span>ou você pode </span>
                    <button
                        type="button"
                        :class="linkClasses"
                        @click="() => toggleRecoveryMode(clearErrors)"
                    >
                        {{ authConfigContent.buttonText }}
                    </button>
                </div>
            </Form>
        </template>
    </div>
</template>
