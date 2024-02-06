import { onMounted, ref } from 'vue'

import { defineStore } from 'pinia'

import { OrderStatusResponse } from '~resources/vue/api/order/models'

import { useOrderStatusesAPI } from '~api/order/status'

import { CacheStorage } from '~cache/cache-storage'
import { TTLValues } from '~cache/enums'

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
