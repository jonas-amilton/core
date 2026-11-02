<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import {
    Building2,
    ClipboardList,
    ExternalLink,
    FileText,
    HelpCircle,
    Image,
    Inbox,
    LayoutGrid,
    MessageSquareQuote,
    Newspaper,
    Package,
    Settings2,
    Star,
    Store,
    TicketPercent,
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
import { index as products } from '@/routes/catalog/product';
import { index as reviews } from '@/routes/catalog/review';
import { index as quotes } from '@/routes/quote';
import { index as settings } from '@/routes/settings';
import { index as stores } from '@/routes/stores';
import { index as testimonials } from '@/routes/testimonial';
import { index as users } from '@/routes/user';
import type { NavItem } from '@/types';
import { computed } from 'vue';

const overviewItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
];

const contentItems: NavItem[] = [
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
        title: 'Produtos',
        href: products(),
        icon: Package,
    },
    {
        title: 'Blog',
        href: blog(),
        icon: Newspaper,
    },
    {
        title: 'Avaliações',
        href: reviews(),
        icon: Star,
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
        title: 'Lojas',
        href: stores(),
        icon: Store,
    },
];

const operationItems: NavItem[] = [
    {
        title: 'Orçamentos',
        href: quotes(),
        icon: ClipboardList,
    },
    {
        title: 'Mensagens',
        href: messages(),
        icon: Inbox,
    },
    {
        title: 'Cupons',
        href: '/painel/orcamentos/cupons',
        icon: TicketPercent,
    },
];

const adminItems: NavItem[] = [
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

const page = usePage();
const isAdmin = computed(
    () =>
        (
            page.props.auth as { user?: { role?: string } | null } | undefined
        )?.user?.role === 'admin',
);

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
        <SidebarHeader class="border-b border-sidebar-border">
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

        <SidebarContent class="gap-3 pt-2">
            <NavMain :items="overviewItems" />
            <NavMain :items="contentItems" label="Conteúdo" />
            <NavMain :items="operationItems" label="Operação" />
            <NavMain v-if="isAdmin" :items="adminItems" label="Administração" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
