import { onMounted } from 'vue'

import { defineStore } from 'pinia'

import { OrderStatusResponse } from '~resources/api/order/models'
import { TTLValues, useLocalStorageCache } from '~resources/lib/locale-storage-cache/locale-storage-cache'

import { useOrderStatusesAPI } from '~api/order/status'

export const useOrderStatusesStore = defineStore('order-statuses', () => {
  const { hasData, setData, getData } = useLocalStorageCache('order-statuses', TTLValues.DAY)
  const { data: statuses, execute: fetchStatuses } = useOrderStatusesAPI()
  onMounted(async () => {
    if (hasData()) {
      statuses.value = getData() as OrderStatusResponse[]
    } else {
      await fetchStatuses()
      setData(statuses.value)
    }
  })

  return {
    statuses,
    fetchStatuses,
  }
})
