import { createFetch } from '@vueuse/core'

export const useAPI = createFetch({
  baseUrl: import.meta.env.VITE_API_URL,
  options: {
    immediate: false,
  },
})

export const useAdminAPI = createFetch({
  baseUrl: import.meta.env.VITE_ADMIN_API_URL,
  options: {
    immediate: false,
  },
})
