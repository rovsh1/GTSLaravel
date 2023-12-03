import { nextTick, onMounted, ref } from 'vue'

import { defineStore } from 'pinia'
import { z } from 'zod'

import {
  createOrderVoucher,
  downloadDocument,
  sendOrderVoucher,
  useOrderVoucherGetAPI,
} from '~api/order/voucher'

import { showConfirmDialog } from '~lib/confirm-dialog'
import { requestInitialData } from '~lib/initial-data'

const { orderID } = requestInitialData('view-initial-data-booking-order', z.object({
  orderID: z.number(),
}))

export const useOrderVoucherStore = defineStore('order-voucher', () => {
  const { data: voucher, execute: fetchOrderVoucher, isFetching } = useOrderVoucherGetAPI({ orderID })
  const voucherCreateIsFetching = ref(false)

  const createVoucher = async () => {
    voucherCreateIsFetching.value = true
    await createOrderVoucher({ orderID })
    await fetchOrderVoucher()
    voucherCreateIsFetching.value = false
  }

  const sendVoucher = async () => {
    const { result: isConfirmed, toggleClose } = await showConfirmDialog('Отправить ваучер?')
    if (isConfirmed) {
      voucherCreateIsFetching.value = true
      nextTick(toggleClose)
      await sendOrderVoucher({ orderID })
      await fetchOrderVoucher()
    }
    voucherCreateIsFetching.value = false
  }

  const downloadFile = async (): Promise<void> => {
    await downloadDocument({ orderID })
  }

  onMounted(() => {
    fetchOrderVoucher()
  })

  return {
    voucher,
    isFetching,
    voucherCreateIsFetching,
    createVoucher,
    sendVoucher,
    downloadFile,
  }
})
