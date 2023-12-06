import { onMounted } from 'vue'

import { defineStore } from 'pinia'

import { OrderStatusResponse } from '~resources/api/order/models'

import { useOrderStatusesAPI } from '~api/order/status'

import useLocalStorageCache from '~lib/locale-storage-cache/index'

export const useOrderStatusesStore = defineStore('order-statuses', () => {
  const { data: dataFromStorage, existData, saveToLocalStorage } = useLocalStorageCache('order-statuses')
  const { data: statuses, execute: fetchStatuses } = useOrderStatusesAPI()
  onMounted(async () => {
    if (existData.value) {
      statuses.value = dataFromStorage.value as OrderStatusResponse[]
    } else {
      await fetchStatuses()
      saveToLocalStorage(statuses.value)
    }
  })

  return {
    statuses,
    fetchStatuses,
  }
})
