import { computed, onMounted } from 'vue'

import { defineStore } from 'pinia'
import { z } from 'zod'

import { downloadDocument as downloadDocumentRequest } from '~api/booking/document'
import { BookingRequest, useBookingRequestListAPI } from '~api/booking/request'

import { requestInitialData } from '~helpers/initial-data'

const { bookingID } = requestInitialData(z.object({
  bookingID: z.number(),
}))

export const useBookingRequestStore = defineStore('booking-requests', () => {
  const { data: requests, execute: fetchBookingRequests, isFetching } = useBookingRequestListAPI({ bookingID })

  const groupedRequests = computed(() => (requests.value ? Object.values(
    requests.value.reduce((acc, bookingRequest) => {
      const { type, dateCreate } = bookingRequest
      if (!acc[type] || dateCreate > acc[type].dateCreate) {
        acc[type] = bookingRequest
      }
      return acc
    }, {} as { [key: number]: BookingRequest }),
  ) : null))

  const downloadDocument = async (requestId: number): Promise<void> => {
    await downloadDocumentRequest({ documentID: requestId, documentType: 'request', bookingID })
  }

  onMounted(() => {
    fetchBookingRequests()
  })

  return {
    requests,
    groupedRequests,
    isFetching,
    fetchBookingRequests,
    downloadDocument,
  }
})
