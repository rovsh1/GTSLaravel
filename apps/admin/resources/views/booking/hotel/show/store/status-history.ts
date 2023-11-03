import { computed } from 'vue'

import { defineStore } from 'pinia'
import { z } from 'zod'

import { BookingStatusHistoryResponse, useBookingStatusHistoryAPI } from '~api/booking/hotel/status'

import { requestInitialData } from '~lib/initial-data'

const { bookingID } = requestInitialData('view-initial-data-hotel-booking', z.object({
  bookingID: z.number(),
}))

export const useBookingStatusHistoryStore = defineStore('booking-status-history', () => {
  const { data: statusHistoryEvents, execute: fetchStatusHistory, isFetching } = useBookingStatusHistoryAPI({ bookingID })

  const lastHistoryItem = computed<BookingStatusHistoryResponse | undefined>(() => {
    if (!statusHistoryEvents.value) {
      return undefined
    }

    return statusHistoryEvents.value[statusHistoryEvents.value.length - 1]
  })

  return {
    statusHistoryEvents,
    fetchStatusHistory,
    isFetching,
    lastHistoryItem,
  }
})
