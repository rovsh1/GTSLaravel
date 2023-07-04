import { ref } from 'vue'

import { defineStore } from 'pinia'
import { z } from 'zod'

import { sendBookingRequest, useBookingRequestListAPI } from '~api/booking/request'

import { showConfirmDialog } from '~lib/confirm-dialog'
import { requestInitialData } from '~lib/initial-data'

const { bookingID } = requestInitialData('view-initial-data-hotel-booking', z.object({
  bookingID: z.number(),
}))

export const useBookingRequestStore = defineStore('booking-requests', () => {
  const { data: requests, execute: fetchBookingRequests, isFetching } = useBookingRequestListAPI({ bookingID })
  const requestSendIsFetching = ref(false)

  const sendRequest = async () => {
    const { result: isConfirmed, toggleClose } = await showConfirmDialog('Отправить запрос?')
    if (isConfirmed) {
      requestSendIsFetching.value = true
      setTimeout(toggleClose)
      await sendBookingRequest({ bookingID })
      await fetchBookingRequests()
    }
    requestSendIsFetching.value = false
  }

  return {
    requests,
    isFetching,
    requestSendIsFetching,
    fetchBookingRequests,
    sendRequest,
  }
})
