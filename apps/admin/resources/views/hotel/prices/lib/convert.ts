export const stringToNumber = (value: string | null): number | null => {
  const numValue = value?.trim() === '' ? NaN : Number(value)
  if (!isNaN(numValue)) {
    return numValue
  }
  return null
}
