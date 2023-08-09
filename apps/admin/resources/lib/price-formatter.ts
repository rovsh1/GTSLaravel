export const priceFormatter = (price: number, currency?: string) : string => {
    const parts = price.toString().split('.');
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
    return parts.join('.') + (currency && currency.length > 0 ? ` ${currency}` : '');
}