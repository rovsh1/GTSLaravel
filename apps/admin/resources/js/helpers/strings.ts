import { camelCase } from 'lodash'

export const toPascalCase = (str: string): string => {
  const camelCasedStr = camelCase(str)
  return camelCasedStr.charAt(0).toUpperCase() + camelCasedStr.slice(1)
}
