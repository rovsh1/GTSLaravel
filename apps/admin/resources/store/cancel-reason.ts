import { onMounted, ref } from 'vue'

import { defineStore } from 'pinia'

import { CacheStorage } from '~resources/lib/cache-storage/cache-storage'
import { TTLValues } from '~resources/lib/enums'

import { CancelReasonResponse, useCancelReasonListAPI } from '~api/cancel-reason'

export const useCancelReasonStore = defineStore('cancel-reasons', () => {
  const cancelReasons = ref<CancelReasonResponse[] | null>(null)
  onMounted(async () => {
    cancelReasons.value = await CacheStorage.remember('cancel-reasons', TTLValues.DAY, async () => {
      const { data, execute: fetchCancelReasons } = useCancelReasonListAPI()
      await fetchCancelReasons()
      return data.value
    }) as CancelReasonResponse[]
  })
  return {
    cancelReasons,
  }
})
