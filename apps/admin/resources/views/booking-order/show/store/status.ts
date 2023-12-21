import { onMounted, ref } from 'vue'

import { defineStore } from 'pinia'

import { OrderStatusResponse } from '~resources/api/order/models'
import { CacheStorage } from '~resources/lib/cache-storage/cache-storage'
import { TTLValues } from '~resources/lib/enums'

import { useOrderStatusesAPI } from '~api/order/status'

export const useOrderStatusesStore = defineStore('order-statuses', () => {
  const statuses = ref<OrderStatusResponse[] | null>(null)
  onMounted(async () => {
    statuses.value = await CacheStorage.remember('order-statuses', TTLValues.DAY, async () => {
      const { data, execute: fetchStatuses } = useOrderStatusesAPI()
      await fetchStatuses()
      return data.value
    }) as OrderStatusResponse[]
  })

  return {
    statuses,
  }
})
