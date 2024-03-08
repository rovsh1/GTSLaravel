import { nextTick, onMounted, ref } from 'vue'

import { showConfirmDialog } from 'gts-common/helpers/confirm-dialog'
import { requestInitialData } from 'gts-common/helpers/initial-data'
import { defineStore } from 'pinia'
import { z } from 'zod'

import {
  cancelOrderInvoice,
  createOrderInvoice,
  downloadDocument,
  sendOrderInvoice,
  useOrderInvoiceGetAPI,
} from '~api/order/invoice'

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
    if (!invoice.value?.document) {
      return
    }
    await downloadDocument({ orderID, guid: invoice.value.document })
  }

  const downloadWordFile = async (): Promise<void> => {
    if (!invoice.value?.wordDocument) {
      return
    }
    await downloadDocument({ orderID, guid: invoice.value.wordDocument })
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
    downloadWordFile,
  }
})
