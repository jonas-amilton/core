<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import { Separator } from '@/components/ui/separator';
import { useCurrentUrl } from '@/composables/useCurrentUrl';
import { toUrl } from '@/lib/utils';
import { edit as editAppearance } from '@/routes/appearance';
import { edit as editProfile } from '@/routes/profile';
import { edit as editSecurity } from '@/routes/security';
import type { NavItem } from '@/types';

const sidebarNavItems: NavItem[] = [
    {
        title: 'Perfil',
        href: editProfile(),
    },
    {
        title: 'Segurança',
        href: editSecurity(),
    },
    {
        title: 'Aparência',
        href: editAppearance(),
    },
];

const { isCurrentOrParentUrl } = useCurrentUrl();
</script>

<template>
    <div class="px-4 py-6 sm:px-6">
        <Heading
            title="Configurações"
            description="Gerencie seu perfil e as preferências da conta"
        />

        <div class="flex flex-col lg:flex-row lg:space-x-12">
            <aside class="w-full max-w-xl lg:w-48">
                <nav
                    class="flex flex-wrap gap-1 lg:flex-col"
                    aria-label="Configurações"
                >
                    <Link
                        v-for="item in sidebarNavItems"
                        :key="toUrl(item.href)"
                        :href="item.href"
                        :class="[
                            'rounded-sm px-3 py-2 text-sm font-medium transition-colors focus-visible:ring-2 focus-visible:ring-orange-600 focus-visible:outline-none',
                            isCurrentOrParentUrl(item.href)
                                ? 'bg-orange-50 text-orange-700 dark:bg-orange-950/50 dark:text-orange-400'
                                : 'text-stone-600 hover:bg-stone-100 hover:text-stone-900 dark:text-stone-400 dark:hover:bg-stone-800/60 dark:hover:text-stone-100',
                        ]"
                    >
                        {{ item.title }}
                    </Link>
                </nav>
            </aside>

            <Separator class="my-6 lg:hidden" />

            <div class="flex-1 md:max-w-2xl">
                <section class="max-w-xl space-y-10">
                    <slot />
                </section>
            </div>
        </div>
    </div>
</template>
