import { onMounted, ref } from 'vue'

import { defineStore } from 'pinia'

import { BookingStatusResponse } from '~api/booking/models'
import { useBookingStatusesAPI } from '~api/booking/status'

import { CacheStorage } from '~cache/cache-storage'
import { TTLValues } from '~cache/enums'

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
