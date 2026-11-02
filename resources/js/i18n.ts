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
        quote: 'Carrinho',
        panel: 'Painel',
        account: 'Minha conta',
    },
    hero: {
        eyebrow: 'Loja da fábrica',
        benefit1: 'Fabricação própria',
        benefit2: 'Preços publicados no catálogo',
        benefit3: 'Envio para todo o Brasil',
        secondary: 'Solicitar orçamento',
        support: 'Atendimento técnico',
        supportTitle: 'Fale com a engenharia',
        supportText:
            'Envie o desenho, a foto ou a amostra. Devolvemos a proposta com material e potência definidos.',
        supportCta: 'Enviar desenho ou amostra',
        years: 'anos de mercado',
        clients: 'clientes atendidos',
    },
    footer: {
        contact: 'Contato',
        social: 'Redes',
        navigation: 'Navegação',
        help: 'Ajuda',
    },
    clients: {
        supply: 'Fornecimento contínuo',
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
        read: 'Ler artigo',
        categoriesAria: 'Categorias do blog',
    },
    account: {
        title: 'Minha conta',
        greeting: 'Olá',
        orders: 'Meus pedidos',
        order: 'Pedido',
        statusOpen: 'Em análise',
        statusAnswered: 'Respondido',
        logout: 'Sair',
        empty: 'Você ainda não fez nenhum pedido.',
        favorites: 'Favoritos',
        emptyFavorites: 'Nenhum produto salvo ainda.',
    },
    reviews: {
        title: 'Avaliações',
        empty: 'Este produto ainda não tem avaliações.',
        formTitle: 'Deixe sua avaliação',
        name: 'Nome',
        email: 'E-mail',
        rating: 'Nota',
        body: 'Comentário',
        submit: 'Enviar avaliação',
        success: 'Avaliação enviada. Ela aparece após a moderação.',
    },
    search: {
        title: 'Busca',
        placeholder: 'Buscar produtos, notícias e páginas',
        submit: 'Buscar',
        resultsFor: 'Resultados para “:term”',
        empty: 'Nenhum resultado encontrado.',
        products: 'Produtos',
        posts: 'Notícias',
        pages: 'Páginas',
    },
    catalog: {
        title: 'Produtos',
        empty: 'Nenhum produto publicado ainda.',
        sku: 'SKU',
        categoriesAria: 'Categorias de produtos',
        price: 'Preço',
        priceOnRequest: 'Sob consulta',
        favorite: 'Salvar',
        favorited: 'Salvo',
        installments: 'ou :countx de :value',
        outOfStock: 'Esgotado',
        inStock: ':count em estoque',
        addToCart: 'Adicionar ao carrinho',
        viewCart: 'Ver carrinho',
        added: 'Adicionado ao carrinho',
        related: 'Produtos relacionados',
        view: 'Ver produto',
        itemsCount: ':count produtos',
        minPrice: 'mín.',
        maxPrice: 'máx.',
        apply: 'Aplicar',
        sort: 'Ordenar por',
        sortDefault: 'Relevância',
        sortPriceAsc: 'Menor preço',
        sortPriceDesc: 'Maior preço',
        sortName: 'Nome',
        sortNewest: 'Mais recentes',
        recentlyViewed: 'Vistos recentemente',
    },
    quote: {
        title: 'Carrinho',
        subtitle: 'Confira os itens, informe seus dados e finalize o pedido.',
        success:
            'Pedido enviado. Entraremos em contato para combinar pagamento e entrega.',
        empty: 'Seu carrinho está vazio.',
        viewProducts: 'Ver produtos',
        name: 'Nome',
        email: 'E-mail',
        phone: 'Telefone (opcional)',
        message: 'Observações',
        submit: 'Finalizar pedido',
        emptyCart: 'Adicione ao menos um produto ao carrinho.',
        quantity: 'Qtd.',
        remove: 'Remover',
        total: 'Total',
        subtotal: 'Subtotal',
        discount: 'Desconto',
        coupon: 'Cupom',
        applyCoupon: 'Aplicar',
        removeCoupon: 'Remover cupom',
        couponPlaceholder: 'Código do cupom',
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
        quote: 'Cart',
        panel: 'Dashboard',
        account: 'My account',
    },
    hero: {
        eyebrow: 'Factory store',
        benefit1: 'Made in-house',
        benefit2: 'Prices published in the catalog',
        benefit3: 'Shipping nationwide',
        secondary: 'Request a quote',
        support: 'Technical support',
        supportTitle: 'Talk to engineering',
        supportText:
            'Send the drawing, photo or sample. We reply with a proposal and the material and power defined.',
        supportCta: 'Send drawing or sample',
        years: 'years in business',
        clients: 'clients served',
    },
    footer: {
        contact: 'Contact',
        social: 'Social',
        navigation: 'Navigation',
        help: 'Help',
    },
    clients: {
        supply: 'Ongoing supply',
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
        read: 'Read article',
        categoriesAria: 'Blog categories',
    },
    account: {
        title: 'My account',
        greeting: 'Hello',
        orders: 'My orders',
        order: 'Order',
        statusOpen: 'Under review',
        statusAnswered: 'Answered',
        logout: 'Sign out',
        empty: 'You have not placed any order yet.',
        favorites: 'Favorites',
        emptyFavorites: 'No saved products yet.',
    },
    reviews: {
        title: 'Reviews',
        empty: 'This product has no reviews yet.',
        formTitle: 'Leave a review',
        name: 'Name',
        email: 'Email',
        rating: 'Rating',
        body: 'Comment',
        submit: 'Submit review',
        success: 'Review sent. It appears after moderation.',
    },
    search: {
        title: 'Search',
        placeholder: 'Search products, news and pages',
        submit: 'Search',
        resultsFor: 'Results for “:term”',
        empty: 'No results found.',
        products: 'Products',
        posts: 'News',
        pages: 'Pages',
    },
    catalog: {
        title: 'Products',
        empty: 'No products published yet.',
        sku: 'SKU',
        categoriesAria: 'Product categories',
        price: 'Price',
        priceOnRequest: 'Price on request',
        favorite: 'Save',
        favorited: 'Saved',
        installments: 'or :countx of :value',
        outOfStock: 'Sold out',
        inStock: ':count in stock',
        addToCart: 'Add to cart',
        viewCart: 'View cart',
        added: 'Added to cart',
        related: 'Related products',
        view: 'View product',
        itemsCount: ':count products',
        minPrice: 'min.',
        maxPrice: 'max.',
        apply: 'Apply',
        sort: 'Sort by',
        sortDefault: 'Relevance',
        sortPriceAsc: 'Lowest price',
        sortPriceDesc: 'Highest price',
        sortName: 'Name',
        sortNewest: 'Newest',
        recentlyViewed: 'Recently viewed',
    },
    quote: {
        title: 'Cart',
        subtitle: 'Review the items, fill in your details and place the order.',
        success:
            'Order sent. We will contact you to arrange payment and delivery.',
        empty: 'Your cart is empty.',
        viewProducts: 'View products',
        name: 'Name',
        email: 'Email',
        phone: 'Phone (optional)',
        message: 'Notes',
        submit: 'Place order',
        emptyCart: 'Add at least one product to the cart.',
        quantity: 'Qty.',
        remove: 'Remove',
        total: 'Total',
        subtotal: 'Subtotal',
        discount: 'Discount',
        coupon: 'Coupon',
        applyCoupon: 'Apply',
        removeCoupon: 'Remove coupon',
        couponPlaceholder: 'Coupon code',
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
        quote: 'Carrito',
        panel: 'Panel',
        account: 'Mi cuenta',
    },
    hero: {
        eyebrow: 'Tienda de fábrica',
        benefit1: 'Fabricación propia',
        benefit2: 'Precios publicados en el catálogo',
        benefit3: 'Envíos a todo el país',
        secondary: 'Solicitar presupuesto',
        support: 'Atención técnica',
        supportTitle: 'Habla con ingeniería',
        supportText:
            'Envía el plano, la foto o la muestra. Respondemos con la propuesta y el material y la potencia definidos.',
        supportCta: 'Enviar plano o muestra',
        years: 'años de mercado',
        clients: 'clientes atendidos',
    },
    footer: {
        contact: 'Contacto',
        social: 'Redes',
        navigation: 'Navegación',
        help: 'Ayuda',
    },
    clients: {
        supply: 'Suministro continuo',
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
        read: 'Leer artículo',
        categoriesAria: 'Categorías del blog',
    },
    account: {
        title: 'Mi cuenta',
        greeting: 'Hola',
        orders: 'Mis pedidos',
        order: 'Pedido',
        statusOpen: 'En análisis',
        statusAnswered: 'Respondido',
        logout: 'Salir',
        empty: 'Todavía no hiciste ningún pedido.',
        favorites: 'Favoritos',
        emptyFavorites: 'Ningún producto guardado todavía.',
    },
    reviews: {
        title: 'Valoraciones',
        empty: 'Este producto aún no tiene valoraciones.',
        formTitle: 'Deja tu valoración',
        name: 'Nombre',
        email: 'Correo electrónico',
        rating: 'Nota',
        body: 'Comentario',
        submit: 'Enviar valoración',
        success: 'Valoración enviada. Aparecerá tras la moderación.',
    },
    search: {
        title: 'Búsqueda',
        placeholder: 'Buscar productos, noticias y páginas',
        submit: 'Buscar',
        resultsFor: 'Resultados para “:term”',
        empty: 'No se encontraron resultados.',
        products: 'Productos',
        posts: 'Noticias',
        pages: 'Páginas',
    },
    catalog: {
        title: 'Productos',
        empty: 'Aún no hay productos publicados.',
        sku: 'SKU',
        categoriesAria: 'Categorías de productos',
        price: 'Precio',
        priceOnRequest: 'Precio a consultar',
        favorite: 'Guardar',
        favorited: 'Guardado',
        installments: 'o :countx de :value',
        outOfStock: 'Agotado',
        inStock: ':count en stock',
        addToCart: 'Añadir al carrito',
        viewCart: 'Ver carrito',
        added: 'Añadido al carrito',
        related: 'Productos relacionados',
        view: 'Ver producto',
        itemsCount: ':count productos',
        minPrice: 'mín.',
        maxPrice: 'máx.',
        apply: 'Aplicar',
        sort: 'Ordenar por',
        sortDefault: 'Relevancia',
        sortPriceAsc: 'Menor precio',
        sortPriceDesc: 'Mayor precio',
        sortName: 'Nombre',
        sortNewest: 'Más recientes',
        recentlyViewed: 'Vistos recientemente',
    },
    quote: {
        title: 'Carrito',
        subtitle: 'Revisa los artículos, completa tus datos y finaliza el pedido.',
        success:
            'Pedido enviado. Nos pondremos en contacto para acordar pago y entrega.',
        empty: 'Tu carrito está vacío.',
        viewProducts: 'Ver productos',
        name: 'Nombre',
        email: 'Correo electrónico',
        phone: 'Teléfono (opcional)',
        message: 'Observaciones',
        submit: 'Finalizar pedido',
        emptyCart: 'Añade al menos un producto al carrito.',
        quantity: 'Cant.',
        remove: 'Quitar',
        total: 'Total',
        subtotal: 'Subtotal',
        discount: 'Descuento',
        coupon: 'Cupón',
        applyCoupon: 'Aplicar',
        removeCoupon: 'Quitar cupón',
        couponPlaceholder: 'Código del cupón',
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
