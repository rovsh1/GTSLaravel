import { ZodObject, ZodRawShape } from 'zod/lib/types'

const globalDataKey = 'view-initial-data'

declare global {
  interface Window {
    [key: string]: ZodRawShape
  }
}

export const parseInitialData = <T extends ZodRawShape>(schema: ZodObject<T>, data: ZodRawShape | undefined) => {
  if (data === undefined) {
    throw new Error('Initial data not provided')
  }
  return schema.parse(data)
}

export const requestInitialData = <T extends ZodRawShape>(schema: ZodObject<T>) =>
  parseInitialData(schema, window[globalDataKey])
