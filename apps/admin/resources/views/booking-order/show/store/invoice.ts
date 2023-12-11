import { nextTick, onMounted, ref } from 'vue'

import { defineStore } from 'pinia'
import { z } from 'zod'

import {
  cancelOrderInvoice,
  createOrderInvoice,
  downloadDocument,
  sendOrderInvoice,
  useOrderInvoiceGetAPI,
} from '~api/order/invoice'

import { showConfirmDialog } from '~lib/confirm-dialog'
import { requestInitialData } from '~lib/initial-data'

const { orderID } = requestInitialData(z.object({
  orderID: z.number(),
}))

export const useOrderInvoiceStore = defineStore('order-invoice', () => {
  const { data: invoice, execute: fetchOrderInvoice, isFetching } = useOrderInvoiceGetAPI({ orderID })
  const invoiceCreateIsFetching = ref(false)

  const createInvoice = async () => {
    invoiceCreateIsFetching.value = true
    await createOrderInvoice({ orderID })
    await fetchOrderInvoice()
    invoiceCreateIsFetching.value = false
  }

  const sendInvoice = async () => {
    const { result: isConfirmed, toggleClose } = await showConfirmDialog('Отправить инвойс?')
    if (isConfirmed) {
      invoiceCreateIsFetching.value = true
      nextTick(toggleClose)
      await sendOrderInvoice({ orderID })
      await fetchOrderInvoice()
    }
    invoiceCreateIsFetching.value = false
  }

  const cancelInvoice = async () => {
    const { result: isConfirmed, toggleClose } = await showConfirmDialog('Отменить инвойс?')
    if (isConfirmed) {
      invoiceCreateIsFetching.value = true
      nextTick(toggleClose)
      await cancelOrderInvoice({ orderID })
      await fetchOrderInvoice()
    }
    invoiceCreateIsFetching.value = false
  }

  const downloadFile = async (): Promise<void> => {
    await downloadDocument({ orderID })
  }

  onMounted(() => {
    fetchOrderInvoice()
  })

  return {
    invoice,
    isFetching,
    invoiceCreateIsFetching,
    createInvoice,
    cancelInvoice,
    sendInvoice,
    downloadFile,
  }
})
