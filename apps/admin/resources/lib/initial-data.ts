import { ZodObject, ZodRawShape } from 'zod/lib/types'

declare global {
  interface Window {
    'view-initial-data'?: ZodRawShape
  }
}

export const parseInitialData = <T extends ZodRawShape>(schema: ZodObject<T>, data: ZodRawShape | undefined) => {
  if (data === undefined) {
    throw new Error('Initial data not provided')
  }
  return schema.parse(data)
}

export const requestInitialData = <T extends ZodRawShape>(schema: ZodObject<T>) =>
  parseInitialData(schema, window['view-initial-data'])
