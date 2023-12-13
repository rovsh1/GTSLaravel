import { defineStore } from 'pinia'
import { z } from 'zod'

import { useBookingStatusHistoryAPI } from '~api/booking/status'

import { requestInitialData } from '~lib/initial-data'

const { bookingID } = requestInitialData(z.object({
  bookingID: z.number(),
}))

export const useBookingStatusHistoryStore = defineStore('booking-status-history', () => {
  const { data: statusHistoryEvents, execute: fetchStatusHistory, isFetching } = useBookingStatusHistoryAPI({ bookingID })

  return {
    statusHistoryEvents,
    fetchStatusHistory,
    isFetching,
  }
})
