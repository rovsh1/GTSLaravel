import { onMounted } from 'vue'

import { defineStore } from 'pinia'

import { BookingStatusResponse } from '~resources/api/booking/models'
import { TTLValues, useLocalStorageCache } from '~resources/lib/locale-storage-cache/locale-storage-cache'

import { useBookingStatusesAPI } from '~api/booking/status'

export const useBookingStatusesStore = defineStore('booking-statuses', () => {
  const { hasData, setData, getData } = useLocalStorageCache('booking-statuses', TTLValues.DAY)
  const { data: statuses, execute: fetchStatuses } = useBookingStatusesAPI()
  onMounted(async () => {
    if (hasData()) {
      statuses.value = getData() as BookingStatusResponse[]
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
