export type BlockType =
    | 'hero'
    | 'text'
    | 'grid'
    | 'image'
    | 'cta'
    | 'services'
    | 'testimonials'
    | 'faq'
    | 'clients'
    | 'posts'
    | 'products'
    | 'contact'
    | 'career'
    | 'newsletter';

export type GridItem = {
    title: string;
    description?: string | null;
    role?: string | null;
    avatar?: string | null;
    logo?: string | null;
    url?: string | null;
    icon?: string | null;
    cover?: string | null;
    category?: string | null;
    date?: string | null;
};

/**
 * Mirrors Modules/Page/app/Support/BlockSchema.php. Fields are nullable because
 * the PHP normalizer writes `null` for optional values.
 */
export type Block = {
    id: string;
    type: BlockType;
    hidden?: boolean;
    title?: string | null;
    subtitle?: string | null;
    body?: string | null;
    src?: string | null;
    alt?: string | null;
    caption?: string | null;
    media_id?: number | null;
    description?: string | null;
    button_label?: string | null;
    button_href?: string | null;
    limit?: number | null;
    items?: GridItem[];
};

export type PageStatus = 'draft' | 'published' | 'scheduled';

export type PageSummary = {
    id: number;
    title: string;
    slug: string;
    icon: string | null;
    is_service: boolean;
    status: PageStatus;
    published_at: string | null;
    updated_at: string | null;
    is_home: boolean;
    is_not_found: boolean;
    is_system: boolean;
};

export type NavigationItem = {
    title: string;
    slug: string;
    url: string;
};

export type PageRecord = PageSummary & {
    blocks: Block[];
};

export type Revision = {
    id: number;
    title: string;
    author: string | null;
    created_at: string | null;
};

export type PickedMedia = {
    id: number;
    url: string;
    alt: string | null;
    original_name: string;
};
