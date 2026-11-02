const brl = new Intl.NumberFormat('pt-BR', {
    style: 'currency',
    currency: 'BRL',
});

export function formatPrice(
    value: number | string | null | undefined,
): string | null {
    if (value === null || value === undefined || value === '') {
        return null;
    }

    const amount = typeof value === 'number' ? value : Number(value);

    return Number.isFinite(amount) ? brl.format(amount) : null;
}
