/**
 * Shared Tailwind classes for the CoreCMS forms. The panel uses the same
 * paper/ink language as the site: hairline borders, flat surfaces, one accent.
 */
export const fieldClasses =
    'h-11 rounded-sm border border-stone-300 bg-background px-3.5 text-sm text-stone-900 placeholder:text-stone-500 hover:border-stone-400 focus-visible:border-orange-700 focus-visible:ring-1 focus-visible:ring-orange-700/40 dark:border-stone-700 dark:bg-stone-900 dark:text-stone-100 dark:placeholder:text-stone-400 dark:hover:border-stone-600';

export const labelClasses = 'text-stone-700 dark:text-stone-300';

export const linkClasses =
    'rounded-sm font-medium text-orange-800 underline-offset-4 transition-colors hover:underline focus-visible:ring-2 focus-visible:ring-orange-700 focus-visible:outline-none dark:text-orange-400';

export const primaryButtonClasses =
    'h-11 w-full rounded-sm bg-stone-900 font-medium text-stone-50 transition-colors hover:bg-stone-800 disabled:opacity-70 dark:bg-stone-100 dark:text-stone-900 dark:hover:bg-stone-200';

export const statusClasses =
    'mb-6 rounded-sm border border-emerald-200 bg-emerald-50 px-3 py-2 text-center text-sm font-medium text-emerald-800 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-300';

export const cardClasses =
    'rounded-sm border border-stone-200 bg-card p-6 dark:border-stone-800';

/**
 * Public-site fields. Slightly softer than the panel so the institutional site
 * reads as a normal corporate form, not an editorial page.
 */
export const siteFieldClasses =
    'w-full rounded-md border border-slate-300 bg-white px-3.5 text-sm text-slate-900 placeholder:text-slate-400 hover:border-slate-400 focus-visible:border-brand focus-visible:ring-2 focus-visible:ring-brand/20 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:placeholder:text-slate-500 dark:hover:border-slate-600';
