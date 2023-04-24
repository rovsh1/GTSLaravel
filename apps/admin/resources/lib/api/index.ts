import { createFetch } from '@vueuse/core'

import { ADMIN_API_URL, API_URL } from '~resources/lib/env'

export const useAPI = createFetch({
  baseUrl: API_URL,
  options: {
    immediate: false,
  },
})

export const useAdminAPI = createFetch({
  baseUrl: ADMIN_API_URL,
  options: {
    immediate: false,
  },
})
