export const getPriceStatusClass = (seasonPrice: number | null, currentDayPrice: number | null): string => {
  if (currentDayPrice) {
    if (currentDayPrice === seasonPrice) {
      return 'isActive'
    }
    return 'isUnActive'
  }
  return ''
}
