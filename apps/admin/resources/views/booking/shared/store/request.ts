import { onMounted, ref } from 'vue'

import { defineStore } from 'pinia'
import { z } from 'zod'

import { downloadDocument as downloadDocumentRequest } from '~api/booking/document'
import { sendBookingRequest, useBookingRequestListAPI } from '~api/booking/request'

import { showConfirmDialog } from '~lib/confirm-dialog'
import { isInitialDataExists, requestInitialData, ViewInitialDataKey } from '~lib/initial-data'

let initialDataKey: ViewInitialDataKey = 'view-initial-data-hotel-booking'
if (isInitialDataExists('view-initial-data-service-booking')) {
  initialDataKey = 'view-initial-data-service-booking'
}

const { bookingID } = requestInitialData(initialDataKey, z.object({
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
