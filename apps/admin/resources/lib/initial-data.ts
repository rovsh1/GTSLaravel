import { ZodObject, ZodRawShape } from 'zod/lib/types'

export type ViewInitialDataKey = `view-initial-data-${string}`

declare global {
  interface Window {
    [key: ViewInitialDataKey]: ZodRawShape
  }
}

export const parseInitialData = <T extends ZodRawShape>(schema: ZodObject<T>, data: ZodRawShape | undefined) => {
  if (data === undefined) {
    throw new Error('Initial data not provided')
  }
  return schema.parse(data)
}

export const requestInitialData = <T extends ZodRawShape>(key: ViewInitialDataKey, schema: ZodObject<T>) =>
  parseInitialData(schema, window[key])

export const isInitialDataExists = (key: ViewInitialDataKey): boolean => Boolean(window[key])
