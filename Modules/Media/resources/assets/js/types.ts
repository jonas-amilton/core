export type MediaKindValue = 'image' | 'document' | 'other';

export type MediaItem = {
    id: number;
    url: string;
    original_name: string;
    mime: string;
    kind: MediaKindValue;
    size: number;
    width: number | null;
    height: number | null;
    alt: string | null;
    caption: string | null;
    created_at: string | null;
};

export type MediaFilters = {
    search: string;
    kind: MediaKindValue | null;
};

export type MediaKindOption = {
    value: MediaKindValue;
    label: string;
};

export const formatSize = (bytes: number): string => {
    if (bytes < 1024) {
        return `${bytes} B`;
    }

    if (bytes < 1024 * 1024) {
        return `${(bytes / 1024).toFixed(0)} KB`;
    }

    return `${(bytes / (1024 * 1024)).toFixed(1)} MB`;
};
