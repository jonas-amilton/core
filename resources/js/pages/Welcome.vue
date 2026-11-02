<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ArrowRight, Blocks, LayoutDashboard, LayoutGrid } from '@lucide/vue';
import { computed } from 'vue';
import { admin, dashboard, login } from '@/routes';

const props = withDefaults(
    defineProps<{
        canLogin?: boolean;
        canRegister?: boolean;
    }>(),
    {
        canLogin: true,
        canRegister: true,
    },
);

const page = usePage();

const isAuthenticated = computed(() => Boolean(page.props.auth?.user));

const primaryCta = computed(() => {
    if (isAuthenticated.value) {
        return { label: 'Abrir painel', href: dashboard() };
    }

    if (props.canLogin) {
        return { label: 'Acessar o painel', href: login() };
    }

    return null;
});

const features = [
    {
        icon: Blocks,
        title: 'Estrutura Modular',
        description:
            'Cada área do site é um módulo independente. Adicione ou remova domínios como páginas, mídia e usuários sem mexer no resto do painel.',
    },
    {
        icon: LayoutGrid,
        title: 'Blocos Visuais Sob Demanda',
        description:
            'Monte cada página com blocos de Hero, Grid e CTA direto na interface. O conteúdo publicado segue o mesmo padrão visual do site.',
    },
    {
        icon: LayoutDashboard,
        title: 'Painel Unificado',
        description:
            'Páginas, biblioteca de mídia e permissões em um único lugar, com modo claro e escuro em toda a administração.',
    },
];

const currentYear = new Date().getFullYear();
</script>

<template>
    <Head title="CoreCMS — painel do site" />

    <div
        class="min-h-screen bg-white text-stone-900 antialiased dark:bg-stone-950 dark:text-stone-100"
    >
        <!-- Header -->
        <header
            class="sticky top-0 z-50 border-b border-stone-200/80 bg-background dark:border-stone-800/80"
        >
            <div
                class="mx-auto flex h-16 w-full max-w-6xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8"
            >
                <Link
                    :href="admin()"
                    class="flex items-center gap-2.5 rounded-sm text-stone-900 focus-visible:ring-2 focus-visible:ring-orange-600 focus-visible:ring-offset-2 focus-visible:outline-none dark:text-stone-100 dark:focus-visible:ring-offset-stone-950"
                >
                    <span
                        class="flex size-8 items-center justify-center bg-stone-900 text-stone-50 dark:bg-stone-100 dark:text-stone-900"
                    >
                        <Blocks class="size-5" aria-hidden="true" />
                    </span>
                    <span
                        class="font-display text-2xl tracking-tight text-stone-950 dark:text-stone-50"
                    >
                        CoreCMS
                    </span>
                </Link>

                <nav
                    aria-label="Navegação principal"
                    class="hidden items-center gap-8 md:flex"
                >
                    <a
                        href="#editor"
                        class="rounded-sm text-sm font-medium text-stone-600 transition-colors hover:text-orange-700 focus-visible:ring-2 focus-visible:ring-orange-600 focus-visible:outline-none dark:text-stone-400 dark:hover:text-orange-300"
                    >
                        Editor
                    </a>
                    <a
                        href="#recursos"
                        class="rounded-sm text-sm font-medium text-stone-600 transition-colors hover:text-orange-700 focus-visible:ring-2 focus-visible:ring-orange-600 focus-visible:outline-none dark:text-stone-400 dark:hover:text-orange-300"
                    >
                        Recursos
                    </a>
                </nav>

                <div class="flex items-center gap-2">
                    <template v-if="isAuthenticated">
                        <Link
                            :href="dashboard()"
                            class="inline-flex h-9 items-center gap-2 bg-stone-900 px-4 text-sm font-medium text-stone-50 transition-colors hover:bg-stone-800 focus-visible:ring-2 focus-visible:ring-orange-700 focus-visible:outline-none dark:bg-stone-100 dark:text-stone-900 dark:hover:bg-stone-200"
                        >
                            <LayoutDashboard
                                class="size-4"
                                aria-hidden="true"
                            />
                            Painel
                        </Link>
                    </template>
                    <template v-else>
                        <Link
                            v-if="canLogin"
                            :href="login()"
                            class="inline-flex h-9 items-center bg-stone-900 px-4 text-sm font-medium text-stone-50 transition-colors hover:bg-stone-800 focus-visible:ring-2 focus-visible:ring-orange-700 focus-visible:outline-none dark:bg-stone-100 dark:text-stone-900 dark:hover:bg-stone-200"
                        >
                            Entrar
                        </Link>
                    </template>
                </div>
            </div>
        </header>

        <main>
            <!-- Hero -->
            <section class="relative overflow-hidden">
                <div
                    class="mx-auto w-full max-w-6xl px-4 pt-16 pb-20 sm:px-6 sm:pt-24 lg:px-8"
                >
                    <div class="mx-auto max-w-3xl text-center">
                        <span
                            class="inline-flex items-center gap-2 text-[11px] font-medium tracking-[0.16em] text-orange-800 uppercase dark:text-orange-400"
                        >
                            <span
                                class="h-px w-6 bg-orange-700"
                                aria-hidden="true"
                            />
                            Painel de administração do site
                        </span>

                        <h1
                            class="mt-7 font-display text-5xl leading-[0.98] tracking-tight text-balance text-stone-950 sm:text-6xl lg:text-7xl dark:text-stone-50"
                        >
                            Edite o site inteiro, bloco a bloco.
                        </h1>

                        <p
                            class="mx-auto mt-6 max-w-2xl text-base text-pretty text-stone-600 sm:text-lg dark:text-stone-400"
                        >
                            O CoreCMS é o painel onde você monta e ajusta as
                            páginas do site, organiza a biblioteca de mídia e
                            define quem tem acesso. Entre com sua conta para
                            começar a editar.
                        </p>

                        <div
                            class="mt-10 flex flex-col items-center justify-center gap-3 sm:flex-row"
                        >
                            <template v-if="primaryCta">
                                <Link
                                    :href="primaryCta.href"
                                    class="inline-flex h-11 w-full items-center justify-center gap-2 bg-stone-900 px-6 text-sm font-medium text-stone-50 transition-colors hover:bg-stone-800 focus-visible:ring-2 focus-visible:ring-orange-700 focus-visible:outline-none sm:w-auto dark:bg-stone-100 dark:text-stone-900 dark:hover:bg-stone-200"
                                >
                                    {{ primaryCta.label }}
                                    <ArrowRight
                                        class="size-4"
                                        aria-hidden="true"
                                    />
                                </Link>
                            </template>
                            <a
                                href="#editor"
                                class="inline-flex h-11 w-full items-center justify-center border border-stone-400 px-6 text-sm font-medium text-stone-800 transition-colors hover:bg-stone-100 focus-visible:ring-2 focus-visible:ring-orange-700 focus-visible:outline-none sm:w-auto dark:border-stone-600 dark:text-stone-200 dark:hover:bg-stone-900"
                            >
                                Ver o editor
                            </a>
                        </div>

                        <p
                            class="mt-4 text-xs text-stone-500 dark:text-stone-500"
                        >
                            Acesso restrito à administração do site.
                        </p>
                    </div>

                    <!-- Mockup do editor -->
                    <div id="editor" class="mt-16 scroll-mt-24 sm:mt-20">
                        <div
                            class="mx-auto max-w-5xl overflow-hidden border border-stone-300 bg-white dark:border-stone-700 dark:bg-stone-900"
                        >
                            <!-- Barra da janela -->
                            <div
                                class="flex items-center gap-3 border-b border-stone-200 bg-stone-50 px-4 py-2.5 dark:border-stone-800 dark:bg-stone-900/60"
                            >
                                <div class="flex gap-1.5" aria-hidden="true">
                                    <span
                                        class="size-2.5 rounded-full bg-stone-300 dark:bg-stone-700"
                                    />
                                    <span
                                        class="size-2.5 rounded-full bg-stone-300 dark:bg-stone-700"
                                    />
                                    <span
                                        class="size-2.5 rounded-full bg-stone-300 dark:bg-stone-700"
                                    />
                                </div>
                                <div
                                    class="mx-auto hidden max-w-xs flex-1 rounded-sm border border-stone-200 bg-white px-3 py-1 text-center text-[11px] text-stone-400 sm:block dark:border-stone-800 dark:bg-stone-950 dark:text-stone-500"
                                >
                                    corecms.test/pages/1/edit
                                </div>
                                <span
                                    class="rounded-sm bg-emerald-50 px-2 py-1 text-[11px] font-medium text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400"
                                >
                                    Rascunho salvo
                                </span>
                            </div>

                            <div class="flex">
                                <!-- Paleta de blocos -->
                                <aside
                                    aria-hidden="true"
                                    class="hidden w-44 shrink-0 border-r border-stone-200 p-3 sm:block dark:border-stone-800"
                                >
                                    <p
                                        class="px-2 pb-2 text-[10px] font-semibold tracking-wider text-stone-400 uppercase dark:text-stone-500"
                                    >
                                        Blocos
                                    </p>
                                    <ul class="space-y-1.5">
                                        <li
                                            class="flex items-center gap-2 rounded-sm bg-orange-50 px-2.5 py-2 text-xs font-medium text-orange-800 dark:bg-orange-600/10 dark:text-orange-300"
                                        >
                                            <span
                                                class="size-5 rounded bg-orange-200/70 dark:bg-orange-400/20"
                                            />
                                            Hero
                                        </li>
                                        <li
                                            class="flex items-center gap-2 rounded-sm px-2.5 py-2 text-xs text-stone-500 dark:text-stone-400"
                                        >
                                            <span
                                                class="size-5 rounded bg-stone-200 dark:bg-stone-800"
                                            />
                                            Grid
                                        </li>
                                        <li
                                            class="flex items-center gap-2 rounded-sm px-2.5 py-2 text-xs text-stone-500 dark:text-stone-400"
                                        >
                                            <span
                                                class="size-5 rounded bg-stone-200 dark:bg-stone-800"
                                            />
                                            CTA
                                        </li>
                                    </ul>
                                </aside>

                                <!-- Canvas -->
                                <div
                                    class="min-w-0 flex-1 space-y-3 bg-stone-50 p-3 sm:p-5 dark:bg-stone-950/40"
                                >
                                    <!-- Bloco Hero -->
                                    <div
                                        class="group relative bg-stone-900 p-5 sm:p-7 dark:bg-stone-800"
                                    >
                                        <span
                                            class="absolute -top-2.5 left-3 bg-orange-700 px-1.5 py-0.5 text-[10px] font-semibold text-white"
                                        >
                                            Hero
                                        </span>
                                        <div class="max-w-sm space-y-2.5">
                                            <div
                                                class="h-2.5 w-24 rounded-full bg-white/50"
                                            />
                                            <div
                                                class="h-4 w-3/4 rounded-full bg-white/90"
                                            />
                                            <div
                                                class="h-2 w-full rounded-full bg-white/35"
                                            />
                                            <div
                                                class="h-2 w-5/6 rounded-full bg-white/35"
                                            />
                                            <div class="mt-4 flex gap-2">
                                                <div
                                                    class="h-6 w-20 rounded-sm bg-white"
                                                />
                                                <div
                                                    class="h-6 w-16 rounded-sm border border-white/50"
                                                />
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Bloco Grid -->
                                    <div
                                        class="relative rounded-sm border border-stone-200 bg-white p-3 sm:p-4 dark:border-stone-800 dark:bg-stone-900"
                                    >
                                        <span
                                            class="absolute -top-2.5 left-3 rounded-sm bg-stone-100 px-1.5 py-0.5 text-[10px] font-semibold text-stone-500 dark:bg-stone-800 dark:text-stone-400"
                                        >
                                            Grid
                                        </span>
                                        <div
                                            class="grid grid-cols-3 gap-2 sm:gap-3"
                                        >
                                            <div
                                                v-for="index in 3"
                                                :key="index"
                                                class="space-y-2 rounded-sm border border-stone-200 p-2 dark:border-stone-800"
                                            >
                                                <div
                                                    class="size-6 rounded bg-orange-100 dark:bg-orange-600/15"
                                                />
                                                <div
                                                    class="h-1.5 w-full rounded-full bg-stone-200 dark:bg-stone-700"
                                                />
                                                <div
                                                    class="h-1.5 w-2/3 rounded-full bg-stone-200 dark:bg-stone-700"
                                                />
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Bloco CTA -->
                                    <div
                                        class="relative flex flex-col items-center justify-between gap-3 rounded-sm border border-dashed border-stone-300 bg-white px-4 py-4 sm:flex-row sm:py-5 dark:border-stone-700 dark:bg-stone-900"
                                    >
                                        <span
                                            class="absolute -top-2.5 left-3 rounded-sm bg-stone-100 px-1.5 py-0.5 text-[10px] font-semibold text-stone-500 dark:bg-stone-800 dark:text-stone-400"
                                        >
                                            CTA
                                        </span>
                                        <div
                                            class="w-full space-y-1.5 sm:w-auto"
                                        >
                                            <div
                                                class="h-2.5 w-40 max-w-full rounded-full bg-stone-300 dark:bg-stone-700"
                                            />
                                            <div
                                                class="h-1.5 w-56 max-w-full rounded-full bg-stone-200 dark:bg-stone-800"
                                            />
                                        </div>
                                        <div
                                            class="h-7 w-24 rounded-sm bg-orange-700"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Recursos -->
            <section
                id="recursos"
                class="scroll-mt-24 border-t border-stone-200 py-20 sm:py-24 dark:border-stone-800"
            >
                <div class="mx-auto w-full max-w-6xl px-4 sm:px-6 lg:px-8">
                    <div class="max-w-2xl">
                        <h2
                            class="font-display text-3xl tracking-tight text-stone-950 sm:text-4xl dark:text-stone-50"
                        >
                            O que você controla no painel
                        </h2>
                        <p
                            class="mt-3 text-base text-stone-600 dark:text-stone-400"
                        >
                            Tudo o que o site precisa para ser editado no dia a
                            dia, sem sair da administração.
                        </p>
                    </div>

                    <ul class="mt-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                        <li
                            v-for="feature in features"
                            :key="feature.title"
                            class="group border-t border-stone-300 pt-6 transition-colors dark:border-stone-700"
                        >
                            <span
                                class="flex size-6 items-center text-orange-800 dark:text-orange-400"
                            >
                                <component
                                    :is="feature.icon"
                                    class="size-5"
                                    aria-hidden="true"
                                />
                            </span>
                            <h3
                                class="mt-4 text-base font-medium text-stone-950 dark:text-stone-50"
                            >
                                {{ feature.title }}
                            </h3>
                            <p
                                class="mt-2 text-sm leading-relaxed text-stone-600 dark:text-stone-400"
                            >
                                {{ feature.description }}
                            </p>
                        </li>
                    </ul>
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer class="border-t border-stone-200 dark:border-stone-800">
            <div
                class="mx-auto flex w-full max-w-6xl flex-col items-center justify-between gap-3 px-4 py-8 text-sm text-stone-500 sm:flex-row sm:px-6 lg:px-8 dark:text-stone-400"
            >
                <p>
                    © {{ currentYear }} CoreCMS. Todos os direitos reservados.
                </p>
                <p class="flex items-center gap-2 text-xs">
                    <span class="text-stone-400 dark:text-stone-500"
                        >Construído com</span
                    >
                    <span class="font-medium text-stone-600 dark:text-stone-300"
                        >Laravel</span
                    >
                    <span aria-hidden="true">·</span>
                    <span class="font-medium text-stone-600 dark:text-stone-300"
                        >Vue 3</span
                    >
                    <span aria-hidden="true">·</span>
                    <span class="font-medium text-stone-600 dark:text-stone-300"
                        >Inertia.js</span
                    >
                </p>
            </div>
        </footer>
    </div>
</template>
