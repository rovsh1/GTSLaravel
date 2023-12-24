import { onMounted, ref } from 'vue'

import { defineStore } from 'pinia'

import { BookingStatusResponse } from '~resources/api/booking/models'
import { CacheStorage } from '~resources/lib/cache-storage/cache-storage'
import { TTLValues } from '~resources/lib/enums'

import { useBookingStatusesAPI } from '~api/booking/status'

export const useBookingStatusesStore = defineStore('booking-statuses', () => {
  const statuses = ref<BookingStatusResponse[] | null>(null)
  onMounted(async () => {
    statuses.value = await CacheStorage.remember('booking-statuses', TTLValues.DAY, async () => {
      const { data, execute: fetchStatuses } = useBookingStatusesAPI()
      await fetchStatuses()
      return data.value
    }) as BookingStatusResponse[]
  })
  return {
    statuses,
  }
})
