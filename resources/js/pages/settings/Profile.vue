<script setup lang="ts">
import { Form, Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import ProfileController from '@/actions/App/Http/Controllers/Settings/ProfileController';
import DeleteUser from '@/components/DeleteUser.vue';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    cardClasses,
    fieldClasses,
    labelClasses,
    primaryButtonClasses,
} from '@/lib/formStyles';
import { edit } from '@/routes/profile';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Perfil',
                href: edit(),
            },
        ],
    },
});

const page = usePage();
const user = computed(() => page.props.auth.user);
</script>

<template>
    <Head title="Perfil" />

    <h1 class="sr-only">Configurações de perfil</h1>

    <div class="flex flex-col space-y-8">
        <section :class="cardClasses">
            <Heading
                variant="small"
                title="Perfil"
                description="Atualize seu nome e endereço de e-mail"
            />

            <Form
                v-bind="ProfileController.update.form()"
                class="mt-6 space-y-5"
                v-slot="{ errors, processing }"
            >
                <div class="grid gap-2">
                    <Label for="name" :class="labelClasses">Nome</Label>
                    <Input
                        id="name"
                        class="mt-1 block w-full"
                        name="name"
                        :default-value="user.name"
                        required
                        autocomplete="name"
                        placeholder="Nome completo"
                        :class="fieldClasses"
                    />
                    <InputError class="mt-2" :message="errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="email" :class="labelClasses">E-mail</Label>
                    <Input
                        id="email"
                        type="email"
                        class="mt-1 block w-full"
                        name="email"
                        :default-value="user.email"
                        required
                        autocomplete="username"
                        placeholder="voce@empresa.com"
                        :class="fieldClasses"
                    />
                    <InputError class="mt-2" :message="errors.email" />
                </div>

                <div class="flex items-center gap-4 pt-1">
                    <Button
                        :disabled="processing"
                        :aria-busy="processing"
                        :class="[primaryButtonClasses, 'w-auto px-6']"
                        data-test="update-profile-button"
                    >
                        {{ processing ? 'Salvando...' : 'Salvar' }}
                    </Button>
                </div>
            </Form>
        </section>

        <DeleteUser />
    </div>
</template>
