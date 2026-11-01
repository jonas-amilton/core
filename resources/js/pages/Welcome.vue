<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import {
    ArrowRight,
    Blocks,
    LayoutDashboard,
    LayoutGrid,
    Sparkles,
} from '@lucide/vue';
import { computed } from 'vue';
import { admin, home, login } from '@/routes';

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
        return { label: 'Abrir painel', href: admin() };
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
        class="min-h-screen bg-white text-slate-900 antialiased dark:bg-slate-950 dark:text-slate-100"
    >
        <!-- Header -->
        <header
            class="sticky top-0 z-50 border-b border-slate-200/80 bg-white/80 backdrop-blur-md dark:border-slate-800/80 dark:bg-slate-950/80"
        >
            <div
                class="mx-auto flex h-16 w-full max-w-6xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8"
            >
                <Link
                    :href="home()"
                    class="flex items-center gap-2.5 rounded-md text-slate-900 focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 focus-visible:outline-none dark:text-slate-100 dark:focus-visible:ring-offset-slate-950"
                >
                    <span
                        class="flex size-9 items-center justify-center rounded-lg bg-linear-to-br from-indigo-500 to-violet-600 text-white shadow-sm"
                    >
                        <Blocks class="size-5" aria-hidden="true" />
                    </span>
                    <span class="text-lg font-semibold tracking-tight">
                        Core<span class="text-indigo-600 dark:text-indigo-400"
                            >CMS</span
                        >
                    </span>
                </Link>

                <nav
                    aria-label="Navegação principal"
                    class="hidden items-center gap-8 md:flex"
                >
                    <a
                        href="#editor"
                        class="rounded-sm text-sm font-medium text-slate-600 transition-colors hover:text-indigo-600 focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:outline-none dark:text-slate-400 dark:hover:text-indigo-300"
                    >
                        Editor
                    </a>
                    <a
                        href="#recursos"
                        class="rounded-sm text-sm font-medium text-slate-600 transition-colors hover:text-indigo-600 focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:outline-none dark:text-slate-400 dark:hover:text-indigo-300"
                    >
                        Recursos
                    </a>
                </nav>

                <div class="flex items-center gap-2">
                    <template v-if="isAuthenticated">
                        <Link
                            :href="admin()"
                            class="inline-flex h-9 items-center gap-2 rounded-md bg-indigo-600 px-4 text-sm font-medium text-white transition-colors hover:bg-indigo-500 focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 focus-visible:outline-none dark:focus-visible:ring-offset-slate-950"
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
                            class="inline-flex h-9 items-center rounded-md bg-indigo-600 px-4 text-sm font-medium text-white transition-colors hover:bg-indigo-500 focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 focus-visible:outline-none dark:focus-visible:ring-offset-slate-950"
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
                    aria-hidden="true"
                    class="pointer-events-none absolute inset-0 -z-10"
                >
                    <div
                        class="absolute -top-40 left-1/2 h-96 w-[46rem] -translate-x-1/2 rounded-full bg-indigo-500/20 blur-3xl dark:bg-indigo-500/10"
                    />
                    <div
                        class="absolute top-24 -right-24 size-72 rounded-full bg-violet-500/15 blur-3xl dark:bg-violet-500/10"
                    />
                </div>

                <div
                    class="mx-auto w-full max-w-6xl px-4 pt-16 pb-20 sm:px-6 sm:pt-24 lg:px-8"
                >
                    <div class="mx-auto max-w-3xl text-center">
                        <span
                            class="inline-flex items-center gap-2 rounded-full border border-indigo-200 bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-700 dark:border-indigo-400/30 dark:bg-indigo-500/10 dark:text-indigo-300"
                        >
                            <Sparkles class="size-3.5" aria-hidden="true" />
                            Painel de administração do site
                        </span>

                        <h1
                            class="mt-6 text-4xl font-semibold tracking-tight text-balance sm:text-5xl lg:text-6xl"
                        >
                            Edite o site inteiro
                            <span
                                class="bg-linear-to-r from-indigo-600 to-violet-500 bg-clip-text text-transparent dark:from-indigo-400 dark:to-violet-300"
                            >
                                bloco a bloco
                            </span>
                        </h1>

                        <p
                            class="mx-auto mt-6 max-w-2xl text-base text-pretty text-slate-600 sm:text-lg dark:text-slate-400"
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
                                    class="inline-flex h-11 w-full items-center justify-center gap-2 rounded-md bg-indigo-600 px-6 text-sm font-medium text-white shadow-sm transition-colors hover:bg-indigo-500 focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 focus-visible:outline-none sm:w-auto dark:focus-visible:ring-offset-slate-950"
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
                                class="inline-flex h-11 w-full items-center justify-center rounded-md border border-slate-300 bg-white px-6 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50 focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:outline-none sm:w-auto dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800"
                            >
                                Ver o editor
                            </a>
                        </div>

                        <p
                            class="mt-4 text-xs text-slate-500 dark:text-slate-500"
                        >
                            Acesso restrito à administração do site.
                        </p>
                    </div>

                    <!-- Mockup do editor -->
                    <div id="editor" class="mt-16 scroll-mt-24 sm:mt-20">
                        <div
                            class="mx-auto max-w-5xl overflow-hidden rounded-xl border border-slate-200 bg-white shadow-2xl shadow-slate-900/10 dark:border-slate-800 dark:bg-slate-900 dark:shadow-black/40"
                        >
                            <!-- Barra da janela -->
                            <div
                                class="flex items-center gap-3 border-b border-slate-200 bg-slate-50 px-4 py-2.5 dark:border-slate-800 dark:bg-slate-900/60"
                            >
                                <div class="flex gap-1.5" aria-hidden="true">
                                    <span
                                        class="size-2.5 rounded-full bg-slate-300 dark:bg-slate-700"
                                    />
                                    <span
                                        class="size-2.5 rounded-full bg-slate-300 dark:bg-slate-700"
                                    />
                                    <span
                                        class="size-2.5 rounded-full bg-slate-300 dark:bg-slate-700"
                                    />
                                </div>
                                <div
                                    class="mx-auto hidden max-w-xs flex-1 rounded-md border border-slate-200 bg-white px-3 py-1 text-center text-[11px] text-slate-400 sm:block dark:border-slate-800 dark:bg-slate-950 dark:text-slate-500"
                                >
                                    corecms.test/admin/pages/home
                                </div>
                                <span
                                    class="rounded-md bg-emerald-50 px-2 py-1 text-[11px] font-medium text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400"
                                >
                                    Rascunho salvo
                                </span>
                            </div>

                            <div class="flex">
                                <!-- Paleta de blocos -->
                                <aside
                                    aria-hidden="true"
                                    class="hidden w-44 shrink-0 border-r border-slate-200 p-3 sm:block dark:border-slate-800"
                                >
                                    <p
                                        class="px-2 pb-2 text-[10px] font-semibold tracking-wider text-slate-400 uppercase dark:text-slate-500"
                                    >
                                        Blocos
                                    </p>
                                    <ul class="space-y-1.5">
                                        <li
                                            class="flex items-center gap-2 rounded-md bg-indigo-50 px-2.5 py-2 text-xs font-medium text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300"
                                        >
                                            <span
                                                class="size-5 rounded bg-indigo-200/70 dark:bg-indigo-400/20"
                                            />
                                            Hero
                                        </li>
                                        <li
                                            class="flex items-center gap-2 rounded-md px-2.5 py-2 text-xs text-slate-500 dark:text-slate-400"
                                        >
                                            <span
                                                class="size-5 rounded bg-slate-200 dark:bg-slate-800"
                                            />
                                            Grid
                                        </li>
                                        <li
                                            class="flex items-center gap-2 rounded-md px-2.5 py-2 text-xs text-slate-500 dark:text-slate-400"
                                        >
                                            <span
                                                class="size-5 rounded bg-slate-200 dark:bg-slate-800"
                                            />
                                            CTA
                                        </li>
                                    </ul>
                                </aside>

                                <!-- Canvas -->
                                <div
                                    class="min-w-0 flex-1 space-y-3 bg-slate-50 p-3 sm:p-5 dark:bg-slate-950/40"
                                >
                                    <!-- Bloco Hero -->
                                    <div
                                        class="group relative rounded-lg bg-linear-to-br from-indigo-500 via-indigo-600 to-violet-600 p-5 sm:p-7"
                                    >
                                        <span
                                            class="absolute -top-2.5 left-3 rounded-sm bg-indigo-700 px-1.5 py-0.5 text-[10px] font-semibold text-white shadow-sm"
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
                                                    class="h-6 w-20 rounded-md bg-white"
                                                />
                                                <div
                                                    class="h-6 w-16 rounded-md border border-white/50"
                                                />
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Bloco Grid -->
                                    <div
                                        class="relative rounded-lg border border-slate-200 bg-white p-3 sm:p-4 dark:border-slate-800 dark:bg-slate-900"
                                    >
                                        <span
                                            class="absolute -top-2.5 left-3 rounded-sm bg-slate-100 px-1.5 py-0.5 text-[10px] font-semibold text-slate-500 dark:bg-slate-800 dark:text-slate-400"
                                        >
                                            Grid
                                        </span>
                                        <div
                                            class="grid grid-cols-3 gap-2 sm:gap-3"
                                        >
                                            <div
                                                v-for="index in 3"
                                                :key="index"
                                                class="space-y-2 rounded-md border border-slate-200 p-2 dark:border-slate-800"
                                            >
                                                <div
                                                    class="size-6 rounded bg-indigo-100 dark:bg-indigo-500/15"
                                                />
                                                <div
                                                    class="h-1.5 w-full rounded-full bg-slate-200 dark:bg-slate-700"
                                                />
                                                <div
                                                    class="h-1.5 w-2/3 rounded-full bg-slate-200 dark:bg-slate-700"
                                                />
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Bloco CTA -->
                                    <div
                                        class="relative flex flex-col items-center justify-between gap-3 rounded-lg border border-dashed border-slate-300 bg-white px-4 py-4 sm:flex-row sm:py-5 dark:border-slate-700 dark:bg-slate-900"
                                    >
                                        <span
                                            class="absolute -top-2.5 left-3 rounded-sm bg-slate-100 px-1.5 py-0.5 text-[10px] font-semibold text-slate-500 dark:bg-slate-800 dark:text-slate-400"
                                        >
                                            CTA
                                        </span>
                                        <div
                                            class="w-full space-y-1.5 sm:w-auto"
                                        >
                                            <div
                                                class="h-2.5 w-40 max-w-full rounded-full bg-slate-300 dark:bg-slate-700"
                                            />
                                            <div
                                                class="h-1.5 w-56 max-w-full rounded-full bg-slate-200 dark:bg-slate-800"
                                            />
                                        </div>
                                        <div
                                            class="h-7 w-24 rounded-md bg-indigo-600"
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
                class="scroll-mt-24 border-t border-slate-200 py-20 sm:py-24 dark:border-slate-800"
            >
                <div class="mx-auto w-full max-w-6xl px-4 sm:px-6 lg:px-8">
                    <div class="max-w-2xl">
                        <h2
                            class="text-2xl font-semibold tracking-tight sm:text-3xl"
                        >
                            O que você controla no painel
                        </h2>
                        <p
                            class="mt-3 text-base text-slate-600 dark:text-slate-400"
                        >
                            Tudo o que o site precisa para ser editado no dia a
                            dia, sem sair da administração.
                        </p>
                    </div>

                    <ul class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                        <li
                            v-for="feature in features"
                            :key="feature.title"
                            class="group rounded-xl border border-slate-200 bg-white p-6 transition-colors hover:border-indigo-300 dark:border-slate-800 dark:bg-slate-900 dark:hover:border-indigo-500/40"
                        >
                            <span
                                class="flex size-11 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 transition-colors group-hover:bg-indigo-100 dark:bg-indigo-500/10 dark:text-indigo-400 dark:group-hover:bg-indigo-500/20"
                            >
                                <component
                                    :is="feature.icon"
                                    class="size-5"
                                    aria-hidden="true"
                                />
                            </span>
                            <h3 class="mt-5 text-base font-semibold">
                                {{ feature.title }}
                            </h3>
                            <p
                                class="mt-2 text-sm leading-relaxed text-slate-600 dark:text-slate-400"
                            >
                                {{ feature.description }}
                            </p>
                        </li>
                    </ul>
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer class="border-t border-slate-200 dark:border-slate-800">
            <div
                class="mx-auto flex w-full max-w-6xl flex-col items-center justify-between gap-3 px-4 py-8 text-sm text-slate-500 sm:flex-row sm:px-6 lg:px-8 dark:text-slate-400"
            >
                <p>
                    © {{ currentYear }} CoreCMS. Todos os direitos reservados.
                </p>
                <p class="flex items-center gap-2 text-xs">
                    <span class="text-slate-400 dark:text-slate-500"
                        >Construído com</span
                    >
                    <span class="font-medium text-slate-600 dark:text-slate-300"
                        >Laravel</span
                    >
                    <span aria-hidden="true">·</span>
                    <span class="font-medium text-slate-600 dark:text-slate-300"
                        >Vue 3</span
                    >
                    <span aria-hidden="true">·</span>
                    <span class="font-medium text-slate-600 dark:text-slate-300"
                        >Inertia.js</span
                    >
                </p>
            </div>
        </footer>
    </div>
</template>
