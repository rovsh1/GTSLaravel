import { createFetch } from '@vueuse/core'
import qs from 'qs'

import { ADMIN_API_URL } from '~resources/lib/env'

export const useAdminAPI = createFetch({
  baseUrl: ADMIN_API_URL,
  options: {
    immediate: false,
  },
})

export const getURL = (url: string, query?: Record<string, string | number>): string => {
  if (query === undefined) {
    return url
  }
  return `${url}?${qs.stringify(query)}`
}
