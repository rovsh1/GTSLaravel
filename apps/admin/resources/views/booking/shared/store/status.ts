import { onMounted } from 'vue'

import { defineStore } from 'pinia'

import { BookingStatusResponse } from '~resources/api/booking/models'

import { useBookingStatusesAPI } from '~api/booking/status'

import useLocalStorageCache from '~lib/locale-storage-cache/index'

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
