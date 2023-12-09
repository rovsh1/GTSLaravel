import { onMounted } from 'vue'

import { defineStore } from 'pinia'

import { BookingStatusResponse } from '~resources/api/booking/models'
import useLocalStorageCache from '~resources/lib/locale-storage-cache/locale-storage-cache'

import { useBookingStatusesAPI } from '~api/booking/status'

export const useBookingStatusesStore = defineStore('booking-statuses', () => {
  const { data: dataFromStorage, existData, saveToLocalStorage } = useLocalStorageCache('booking-statuses')
  const { data: statuses, execute: fetchStatuses } = useBookingStatusesAPI()
  onMounted(async () => {
    if (existData.value) {
      statuses.value = dataFromStorage.value as BookingStatusResponse[]
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
