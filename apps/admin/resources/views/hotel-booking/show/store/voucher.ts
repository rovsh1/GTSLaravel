import { onMounted, ref } from 'vue'

import { defineStore } from 'pinia'
import { z } from 'zod'

import { downloadDocument as downloadDocumentRequest } from '~api/booking/document'
import { sendBookingVoucher, useBookingVoucherListAPI } from '~api/booking/voucher'

import { showConfirmDialog } from '~lib/confirm-dialog'
import { requestInitialData } from '~lib/initial-data'

const { bookingID } = requestInitialData('view-initial-data-hotel-booking', z.object({
  bookingID: z.number(),
}))

export const useBookingVoucherStore = defineStore('booking-vouchers', () => {
  const { data: vouchers, execute: fetchBookingVouchers, isFetching } = useBookingVoucherListAPI({ bookingID })
  const voucherSendIsFetching = ref(false)

  const sendVoucher = async () => {
    const { result: isConfirmed, toggleClose } = await showConfirmDialog('Отправить ваучер?')
    if (isConfirmed) {
      voucherSendIsFetching.value = true
      setTimeout(toggleClose)
      await sendBookingVoucher({ bookingID })
      await fetchBookingVouchers()
    }
    voucherSendIsFetching.value = false
  }

  const downloadDocument = async (requestId: number): Promise<void> => {
    await downloadDocumentRequest({ documentID: requestId, documentType: 'voucher', bookingID })
  }

  onMounted(() => {
    fetchBookingVouchers()
  })

  return {
    vouchers,
    isFetching,
    voucherSendIsFetching,
    fetchBookingVouchers,
    sendVoucher,
    downloadDocument,
  }
})
