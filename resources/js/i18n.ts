import { router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

export type Locale = 'pt_BR' | 'en' | 'es';

export const localeOptions: { value: Locale; label: string; short: string }[] = [
    { value: 'pt_BR', label: 'Português', short: 'PT' },
    { value: 'en', label: 'English', short: 'EN' },
    { value: 'es', label: 'Español', short: 'ES' },
];

type Dict = { [key: string]: string | Dict };

const ptBR: Dict = {
    nav: {
        pages: 'Páginas do site',
        blog: 'Blog',
        quote: 'Orçamento',
        panel: 'Painel',
    },
    common: {
        all: 'Todos',
        previous: 'Anterior',
        next: 'Próxima',
        pageOf: 'Página :current de :total',
        viewAll: 'Ver todos',
    },
    cookie: {
        aria: 'Aviso de cookies',
        text: 'Este site utiliza cookies para melhorar sua experiência de navegação.',
        policy: 'Política de cookies',
        essential: 'Essenciais',
        essentialDesc:
            'necessários para o funcionamento do site (sessão e proteção contra CSRF). Não podem ser desativados.',
        analytics: 'Análise',
        analyticsDesc: 'métricas anônimas de uso para melhorar o conteúdo.',
        marketing: 'Marketing',
        marketingDesc: 'medição de campanhas e anúncios.',
        onlyEssential: 'Apenas essenciais',
        customize: 'Personalizar',
        acceptAll: 'Aceitar todos',
        save: 'Salvar preferências',
    },
    contact: {
        success: 'Mensagem enviada. Retornaremos em breve.',
        name: 'Nome',
        email: 'E-mail',
        phone: 'Telefone (opcional)',
        message: 'Mensagem',
        submit: 'Enviar mensagem',
    },
    newsletter: {
        success: 'Inscrição confirmada. Obrigado!',
        placeholder: 'seu@email.com',
        aria: 'E-mail para newsletter',
        submit: 'Enviar',
    },
    career: {
        success: 'Currículo recebido. Entraremos em contato.',
        presentation: 'Apresentação',
        submit: 'Enviar',
    },
    posts: { viewAll: 'Ver todos' },
    products: { viewCatalog: 'Ver catálogo' },
    services: {
        empty: 'Nenhuma área publicada. Marque páginas como "Área de atuação" no editor.',
    },
    blog: {
        title: 'Blog',
        all: 'Todos',
        empty: 'Nenhum artigo publicado ainda.',
        readAlso: 'Leia também',
        categoriesAria: 'Categorias do blog',
    },
    catalog: {
        title: 'Produtos',
        empty: 'Nenhum produto publicado ainda.',
        sku: 'SKU',
        categoriesAria: 'Categorias de produtos',
        addToQuote: 'Adicionar ao orçamento',
        viewQuote: 'Ver orçamento',
        added: 'Adicionado',
        related: 'Produtos relacionados',
    },
    quote: {
        title: 'Orçamento',
        subtitle: 'Monte sua lista de produtos e envie. Respondemos por e-mail.',
        success: 'Orçamento enviado. Entraremos em contato em breve.',
        empty: 'Seu orçamento está vazio.',
        viewProducts: 'Ver produtos',
        name: 'Nome',
        email: 'E-mail',
        phone: 'Telefone (opcional)',
        message: 'Observações',
        submit: 'Enviar orçamento',
        emptyCart: 'Adicione ao menos um produto ao orçamento.',
        quantity: 'Qtd.',
        remove: 'Remover',
    },
    stores: {
        title: 'Onde comprar',
        physical: 'Lojas físicas',
        online: 'Lojas virtuais',
        empty: 'Nenhuma loja cadastrada ainda.',
    },
    cookiesPage: {
        title: 'Política de cookies',
        intro: 'Este site utiliza cookies para melhorar sua experiência de navegação.',
        essentialHeading: 'Cookies essenciais',
        essentialIntro:
            'Necessários para o funcionamento do site. Não exigem consentimento.',
        optionalHeading: 'Cookies opcionais',
        optionalIntro:
            'Só são gravados se você aceitar todos ou marcar a categoria correspondente em "Personalizar".',
        cookie: 'Cookie',
        purpose: 'Finalidade',
        duration: 'Duração',
        items: {
            session: {
                purpose: 'Identifica a sessão de navegação do usuário.',
                duration: '2 horas',
            },
            xsrf: {
                purpose: 'Protege contra ataques de falsificação de requisições.',
                duration: '2 horas',
            },
            consent: {
                purpose: 'Armazena as preferências de consentimento de cookies.',
                duration: '1 ano',
            },
            analytics: {
                purpose: 'Coleta informações anônimas sobre como o site é usado.',
                duration: '6 horas',
            },
            tagManager: {
                purpose: 'Carrega e gerencia tags de análise e marketing.',
                duration: '6 horas',
            },
            pixel: {
                purpose: 'Mede conversões e o desempenho de anúncios.',
                duration: '6 horas',
            },
        },
    },
};

const en: Dict = {
    nav: {
        pages: 'Site pages',
        blog: 'Blog',
        quote: 'Quote',
        panel: 'Dashboard',
    },
    common: {
        all: 'All',
        previous: 'Previous',
        next: 'Next',
        pageOf: 'Page :current of :total',
        viewAll: 'View all',
    },
    cookie: {
        aria: 'Cookie notice',
        text: 'This site uses cookies to improve your browsing experience.',
        policy: 'Cookie policy',
        essential: 'Essential',
        essentialDesc:
            'required for the site to work (session and CSRF protection). They cannot be disabled.',
        analytics: 'Analytics',
        analyticsDesc: 'anonymous usage metrics that help improve the content.',
        marketing: 'Marketing',
        marketingDesc: 'campaign and ad measurement.',
        onlyEssential: 'Essential only',
        customize: 'Customize',
        acceptAll: 'Accept all',
        save: 'Save preferences',
    },
    contact: {
        success: 'Message sent. We will get back to you soon.',
        name: 'Name',
        email: 'Email',
        phone: 'Phone (optional)',
        message: 'Message',
        submit: 'Send message',
    },
    newsletter: {
        success: 'Subscription confirmed. Thank you!',
        placeholder: 'you@email.com',
        aria: 'Newsletter email',
        submit: 'Send',
    },
    career: {
        success: 'Resume received. We will contact you.',
        presentation: 'About you',
        submit: 'Send',
    },
    posts: { viewAll: 'View all' },
    products: { viewCatalog: 'View catalog' },
    services: {
        empty: 'No area published yet. Mark pages as "Service area" in the editor.',
    },
    blog: {
        title: 'Blog',
        all: 'All',
        empty: 'No articles published yet.',
        readAlso: 'Read also',
        categoriesAria: 'Blog categories',
    },
    catalog: {
        title: 'Products',
        empty: 'No products published yet.',
        sku: 'SKU',
        categoriesAria: 'Product categories',
        addToQuote: 'Add to quote',
        viewQuote: 'View quote',
        added: 'Added',
        related: 'Related products',
    },
    quote: {
        title: 'Quote request',
        subtitle: 'Build your product list and send it. We reply by email.',
        success: 'Quote sent. We will contact you soon.',
        empty: 'Your quote is empty.',
        viewProducts: 'View products',
        name: 'Name',
        email: 'Email',
        phone: 'Phone (optional)',
        message: 'Notes',
        submit: 'Send quote',
        emptyCart: 'Add at least one product to the quote.',
        quantity: 'Qty.',
        remove: 'Remove',
    },
    stores: {
        title: 'Where to buy',
        physical: 'Physical stores',
        online: 'Online stores',
        empty: 'No stores registered yet.',
    },
    cookiesPage: {
        title: 'Cookie policy',
        intro: 'This site uses cookies to improve your browsing experience.',
        essentialHeading: 'Essential cookies',
        essentialIntro: 'Required for the site to work. They do not need consent.',
        optionalHeading: 'Optional cookies',
        optionalIntro:
            'Only stored if you accept all or enable the matching category under "Customize".',
        cookie: 'Cookie',
        purpose: 'Purpose',
        duration: 'Duration',
        items: {
            session: {
                purpose: 'Identifies the user browsing session.',
                duration: '2 hours',
            },
            xsrf: {
                purpose: 'Protects against request forgery attacks.',
                duration: '2 hours',
            },
            consent: {
                purpose: 'Stores your cookie consent preferences.',
                duration: '1 year',
            },
            analytics: {
                purpose: 'Collects anonymous information about site usage.',
                duration: '6 hours',
            },
            tagManager: {
                purpose: 'Loads and manages analytics and marketing tags.',
                duration: '6 hours',
            },
            pixel: {
                purpose: 'Measures conversions and ad performance.',
                duration: '6 hours',
            },
        },
    },
};

const es: Dict = {
    nav: {
        pages: 'Páginas del sitio',
        blog: 'Blog',
        quote: 'Presupuesto',
        panel: 'Panel',
    },
    common: {
        all: 'Todos',
        previous: 'Anterior',
        next: 'Siguiente',
        pageOf: 'Página :current de :total',
        viewAll: 'Ver todos',
    },
    cookie: {
        aria: 'Aviso de cookies',
        text: 'Este sitio utiliza cookies para mejorar tu experiencia de navegación.',
        policy: 'Política de cookies',
        essential: 'Esenciales',
        essentialDesc:
            'necesarias para el funcionamiento del sitio (sesión y protección CSRF). No se pueden desactivar.',
        analytics: 'Análisis',
        analyticsDesc: 'métricas anónimas de uso para mejorar el contenido.',
        marketing: 'Marketing',
        marketingDesc: 'medición de campañas y anuncios.',
        onlyEssential: 'Solo esenciales',
        customize: 'Personalizar',
        acceptAll: 'Aceptar todas',
        save: 'Guardar preferencias',
    },
    contact: {
        success: 'Mensaje enviado. Te responderemos pronto.',
        name: 'Nombre',
        email: 'Correo electrónico',
        phone: 'Teléfono (opcional)',
        message: 'Mensaje',
        submit: 'Enviar mensaje',
    },
    newsletter: {
        success: 'Suscripción confirmada. ¡Gracias!',
        placeholder: 'tu@email.com',
        aria: 'Correo para el boletín',
        submit: 'Enviar',
    },
    career: {
        success: 'Currículum recibido. Nos pondremos en contacto.',
        presentation: 'Presentación',
        submit: 'Enviar',
    },
    posts: { viewAll: 'Ver todos' },
    products: { viewCatalog: 'Ver catálogo' },
    services: {
        empty: 'Ninguna área publicada. Marca páginas como "Área de actuación" en el editor.',
    },
    blog: {
        title: 'Blog',
        all: 'Todos',
        empty: 'Aún no hay artículos publicados.',
        readAlso: 'Lee también',
        categoriesAria: 'Categorías del blog',
    },
    catalog: {
        title: 'Productos',
        empty: 'Aún no hay productos publicados.',
        sku: 'SKU',
        categoriesAria: 'Categorías de productos',
        addToQuote: 'Añadir al presupuesto',
        viewQuote: 'Ver presupuesto',
        added: 'Añadido',
        related: 'Productos relacionados',
    },
    quote: {
        title: 'Presupuesto',
        subtitle: 'Arma tu lista de productos y envíala. Respondemos por correo.',
        success: 'Presupuesto enviado. Nos pondremos en contacto pronto.',
        empty: 'Tu presupuesto está vacío.',
        viewProducts: 'Ver productos',
        name: 'Nombre',
        email: 'Correo electrónico',
        phone: 'Teléfono (opcional)',
        message: 'Observaciones',
        submit: 'Enviar presupuesto',
        emptyCart: 'Añade al menos un producto al presupuesto.',
        quantity: 'Cant.',
        remove: 'Quitar',
    },
    stores: {
        title: 'Dónde comprar',
        physical: 'Tiendas físicas',
        online: 'Tiendas online',
        empty: 'Aún no hay tiendas registradas.',
    },
    cookiesPage: {
        title: 'Política de cookies',
        intro: 'Este sitio utiliza cookies para mejorar tu experiencia de navegación.',
        essentialHeading: 'Cookies esenciales',
        essentialIntro:
            'Necesarias para el funcionamiento del sitio. No requieren consentimiento.',
        optionalHeading: 'Cookies opcionales',
        optionalIntro:
            'Solo se guardan si aceptas todas o marcas la categoría correspondiente en "Personalizar".',
        cookie: 'Cookie',
        purpose: 'Finalidad',
        duration: 'Duración',
        items: {
            session: {
                purpose: 'Identifica la sesión de navegación del usuario.',
                duration: '2 horas',
            },
            xsrf: {
                purpose: 'Protege contra ataques de falsificación de peticiones.',
                duration: '2 horas',
            },
            consent: {
                purpose: 'Guarda tus preferencias de consentimiento de cookies.',
                duration: '1 año',
            },
            analytics: {
                purpose: 'Recopila información anónima sobre el uso del sitio.',
                duration: '6 horas',
            },
            tagManager: {
                purpose: 'Carga y gestiona etiquetas de análisis y marketing.',
                duration: '6 horas',
            },
            pixel: {
                purpose: 'Mide conversiones y el rendimiento de anuncios.',
                duration: '6 horas',
            },
        },
    },
};

const dictionaries: Record<Locale, Dict> = { pt_BR: ptBR, en, es };

function lookup(dict: Dict, path: string): string | null {
    const value = path
        .split('.')
        .reduce<unknown>(
            (carry, key) =>
                carry && typeof carry === 'object'
                    ? (carry as Record<string, unknown>)[key]
                    : undefined,
            dict,
        );

    return typeof value === 'string' ? value : null;
}

/**
 * Public-site translations. The admin stays in pt-BR, so only site components
 * use this composable.
 */
export function useI18n() {
    const page = usePage();
    const locale = computed<Locale>(
        () => (page.props.locale as Locale | undefined) ?? 'pt_BR',
    );

    const t = (path: string): string =>
        lookup(dictionaries[locale.value], path) ??
        lookup(ptBR, path) ??
        path;

    return { locale, t, locales: localeOptions };
}

/**
 * Switches the language with an Inertia reload: the same page is requested
 * again with `?locale=`, the middleware stores it in the session, and the
 * component tree re-renders with translated props. No full page reload.
 */
export function switchLocale(locale: Locale): void {
    document.documentElement.lang = locale.replace('_', '-');

    router.reload({ data: { locale } });
}
