import { nextTick, onMounted, ref } from 'vue'

import { defineStore } from 'pinia'
import { z } from 'zod'

import { sendOrderVoucher, useOrderVoucherListAPI } from '~resources/api/order/voucher'

import { downloadDocument as downloadDocumentRequest } from '~api/order/document'

import { showConfirmDialog } from '~lib/confirm-dialog'
import { requestInitialData } from '~lib/initial-data'

const { orderID } = requestInitialData('view-initial-data-booking-order', z.object({
  orderID: z.number(),
}))

export const useOrderVoucherStore = defineStore('order-vouchers', () => {
  const { data: vouchers, execute: fetchOrderVouchers, isFetching } = useOrderVoucherListAPI({ orderID })
  const voucherSendIsFetching = ref(false)

  const sendVoucher = async () => {
    const { result: isConfirmed, toggleClose } = await showConfirmDialog('Отправить ваучер?')
    if (isConfirmed) {
      voucherSendIsFetching.value = true
      nextTick(toggleClose)
      await sendOrderVoucher({ orderID })
      await fetchOrderVouchers()
    }
    voucherSendIsFetching.value = false
  }

  const downloadDocument = async (requestId: number): Promise<void> => {
    await downloadDocumentRequest({ documentID: requestId, documentType: 'voucher', orderID })
  }

  onMounted(() => {
    fetchOrderVouchers()
  })

  return {
    vouchers,
    isFetching,
    voucherSendIsFetching,
    fetchOrderVouchers,
    sendVoucher,
    downloadDocument,
  }
})
