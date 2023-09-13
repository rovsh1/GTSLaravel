export const formatPrice = (price: number, currency?: string) : string => {
  const roundedPrice = price.toFixed(2)
  const [integerPart, fractionalPart] = roundedPrice.split('.')
  const formattedIntegerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, ' ')
  let formattedFractionalPart = fractionalPart
  formattedFractionalPart = formattedFractionalPart.replace(/0+$/, '')
  let formattedPrice = formattedIntegerPart
  if (formattedFractionalPart.length > 0) {
    formattedPrice += `.${formattedFractionalPart}`
  }
  if (currency && currency.length > 0) {
    formattedPrice += ` ${currency}`
  }
  return formattedPrice
}
