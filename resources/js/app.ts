import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { initializeTheme } from '@/composables/useAppearance';
import AppLayout from '@/layouts/AppLayout.vue';
import AuthLayout from '@/layouts/AuthLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { initializeFlashToast } from '@/lib/flashToast';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

void createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name: string) => {
        // suport modulo pages: Inertia::render('User::Index') ou Inertia::render('Media::Index')
        if (name.includes('::')) {
            const [module, page] = name.split('::');
            return resolvePageComponent(
                `../../Modules/${module}/resources/assets/js/Pages/${page}.vue`,
                import.meta.glob<any>([
                    '../../Modules/**/resources/assets/js/Pages/**/*.vue',
                    '../../Modules/**/Resources/assets/js/Pages/**/*.vue',
                ]),
            );
        }

        // Laravel default: Inertia::render('Dashboard')
        return resolvePageComponent(
            `./pages/${name}.vue`,
            import.meta.glob<any>(['./pages/**/*.vue', './Pages/**/*.vue']),
        );
    },
    layout: (name) => {
        switch (true) {
            case name === 'Welcome':
            case name === 'Page::Show':
            case name === 'Page::Preview':
            case name === 'Blog::Feed':
            case name === 'Blog::Article':
            case name === 'Catalog::Index':
            case name === 'Catalog::Show':
            case name === 'Quote::Cart':
            case name === 'Store::WhereToBuy':
            case name === 'Settings::CookiePolicy':
                return null;
            case name.startsWith('auth/'):
                return AuthLayout;
            case name.startsWith('settings/'):
                return [AppLayout, SettingsLayout];
            default:
                return AppLayout;
        }
    },
    withApp: (app) => {
        app.directive('focus', {
            mounted: (el: HTMLElement, shouldFocus) => {
                if (shouldFocus.value !== false) {
                    el.focus();
                }
            },
        });
    },
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on page load...
initializeTheme();

// This will listen for flash toast data from the server...
initializeFlashToast();
