import { defineStore } from 'pinia'
import { z } from 'zod'

import { useBookingRequestListAPI } from '~api/booking/request'

import { requestInitialData } from '~lib/initial-data'

const { bookingID } = requestInitialData('view-initial-data-hotel-booking', z.object({
  bookingID: z.number(),
}))

export const useBookingRequestStore = defineStore('booking-requests', () => {
  const { data: requests, execute: fetchBookingRequests, isFetching } = useBookingRequestListAPI({ bookingID })

  return {
    requests,
    isFetching,
    fetchBookingRequests,
  }
})
