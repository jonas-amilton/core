<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import {
    Building2,
    ExternalLink,
    FileText,
    HelpCircle,
    Image,
    Inbox,
    LayoutGrid,
    MessageSquareQuote,
    Newspaper,
    Settings2,
    Users,
} from '@lucide/vue';
import AppLogo from '@/components/AppLogo.vue';
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard, home } from '@/routes';
import { index as blog } from '@/routes/blog';
import { index as clients } from '@/routes/client';
import { index as messages } from '@/routes/contact/message';
import { index as faq } from '@/routes/faq';
import { index as pages } from '@/routes/page';
import { index as media } from '@/routes/media';
import { index as settings } from '@/routes/settings';
import { index as testimonials } from '@/routes/testimonial';
import { index as users } from '@/routes/user';
import type { NavItem } from '@/types';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Páginas',
        href: pages(),
        icon: FileText,
    },
    {
        title: 'Mídia',
        href: media(),
        icon: Image,
    },
    {
        title: 'Blog',
        href: blog(),
        icon: Newspaper,
    },
    {
        title: 'Depoimentos',
        href: testimonials(),
        icon: MessageSquareQuote,
    },
    {
        title: 'Perguntas frequentes',
        href: faq(),
        icon: HelpCircle,
    },
    {
        title: 'Clientes',
        href: clients(),
        icon: Building2,
    },
    {
        title: 'Mensagens',
        href: messages(),
        icon: Inbox,
    },
    {
        title: 'Usuários',
        href: users(),
        icon: Users,
    },
    {
        title: 'Configurações',
        href: settings(),
        icon: Settings2,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Ver site',
        href: home(),
        icon: ExternalLink,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
