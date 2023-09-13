export const getStatusClassByPrice = (seasonPrice: number | null, currentDayPrice: number | null): string => {
  if (currentDayPrice) {
    if (currentDayPrice === seasonPrice) {
      return 'isActive'
    }
    return 'isUnActive'
  }
  return ''
}

export const getStatusClassByFlag = (status: boolean | null): string => {
  if (status !== null) {
    if (status) {
      return 'isUnActive'
    }
    return 'isActive'
  }
  return ''
}
