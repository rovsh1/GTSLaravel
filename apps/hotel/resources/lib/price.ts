import { PriceItem } from '~resources/vue/api/booking/models'

const priceWithCurrency = (formattedPrice: string, currency?: string): string => {
  let result = formattedPrice
  if (currency && currency.length > 0) {
    result += ` ${currency}`
  }
  return result
}

export const formatPrice = (price: number | undefined, currency?: string) : string => {
  if (price === undefined) {
    return priceWithCurrency('0', currency)
  }
  const roundedPrice = price.toFixed(2)
  const [integerPart, fractionalPart] = roundedPrice.split('.')
  const formattedIntegerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, ' ')
  let formattedFractionalPart = fractionalPart
  formattedFractionalPart = formattedFractionalPart.replace(/0+$/, '')
  let formattedPrice = formattedIntegerPart
  if (formattedFractionalPart.length > 0) {
    formattedPrice += `.${formattedFractionalPart}`
  }
  return priceWithCurrency(formattedPrice, currency)
}

export const formatBookingPrice = (bookingPrice: PriceItem, currency?: string) : string =>
  (bookingPrice.isManual ? formatPrice(bookingPrice.manualValue || undefined, currency)
    : formatPrice(bookingPrice.calculatedValue, currency))
