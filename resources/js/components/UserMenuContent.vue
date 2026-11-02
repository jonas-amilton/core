<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { Check, LogOut, Monitor, Moon, Settings, Sun } from '@lucide/vue';
import {
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
} from '@/components/ui/dropdown-menu';
import UserInfo from '@/components/UserInfo.vue';
import { useAppearance } from '@/composables/useAppearance';
import { logout } from '@/routes';
import { edit } from '@/routes/profile';
import type { User } from '@/types';

type Props = {
    user: User;
};

const handleLogout = () => {
    router.flushAll();
};

defineProps<Props>();

const { appearance, updateAppearance } = useAppearance();

const themes = [
    { value: 'light', label: 'Claro', icon: Sun },
    { value: 'dark', label: 'Escuro', icon: Moon },
    { value: 'system', label: 'Sistema', icon: Monitor },
] as const;
</script>

<template>
    <DropdownMenuLabel class="p-0 font-normal">
        <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
            <UserInfo :user="user" :show-email="true" />
        </div>
    </DropdownMenuLabel>
    <DropdownMenuSeparator />
    <DropdownMenuGroup>
        <DropdownMenuItem :as-child="true">
            <Link class="block w-full cursor-pointer" :href="edit()" prefetch>
                <Settings class="mr-2 h-4 w-4" />
                Configurações
            </Link>
        </DropdownMenuItem>
    </DropdownMenuGroup>
    <DropdownMenuSeparator />
    <DropdownMenuGroup>
        <DropdownMenuLabel
            class="text-xs font-medium text-stone-400 dark:text-stone-500"
        >
            Tema
        </DropdownMenuLabel>
        <DropdownMenuItem
            v-for="theme in themes"
            :key="theme.value"
            class="cursor-pointer"
            @click="updateAppearance(theme.value)"
        >
            <component :is="theme.icon" class="mr-2 h-4 w-4" />
            {{ theme.label }}
            <Check
                v-if="appearance === theme.value"
                class="ml-auto h-4 w-4 text-orange-700 dark:text-orange-400"
            />
        </DropdownMenuItem>
    </DropdownMenuGroup>
    <DropdownMenuSeparator />
    <DropdownMenuItem :as-child="true">
        <Link
            class="block w-full cursor-pointer"
            :href="logout()"
            @click="handleLogout"
            as="button"
            data-test="logout-button"
        >
            <LogOut class="mr-2 h-4 w-4" />
            Sair
        </Link>
    </DropdownMenuItem>
</template>
