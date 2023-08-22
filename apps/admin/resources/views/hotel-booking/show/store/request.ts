import { onMounted, ref } from 'vue'

import { defineStore } from 'pinia'
import { z } from 'zod'

import { downloadDocument as downloadDocumentRequest } from '~api/booking/hotel/document'
import { sendBookingRequest, useBookingRequestListAPI } from '~api/booking/hotel/request'

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

  const downloadDocument = async (requestId: number): Promise<void> => {
    await downloadDocumentRequest({ documentID: requestId, documentType: 'request', bookingID })
  }

  onMounted(() => {
    fetchBookingRequests()
  })

  return {
    requests,
    isFetching,
    requestSendIsFetching,
    fetchBookingRequests,
    sendRequest,
    downloadDocument,
  }
})
