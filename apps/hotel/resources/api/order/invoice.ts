import { MaybeRef } from '@vueuse/core'

import { BaseResponse, DateResponse, useAdminAPI } from '~api'
import { FileResponse } from '~api/hotel/images'
import { OrderID } from '~api/order/models'

import { downloadFile } from '~lib/download-file'

export interface OrderInvoicePayload {
  orderID: OrderID
}

export interface OrderInvoice {
  id: number
  createdAt: DateResponse
}

export interface OrderInvoiceCreateResponse {
  isExternalNumberRequired: boolean
}

export const useOrderInvoiceGetAPI = (props: MaybeRef<OrderInvoicePayload>) =>
  useAdminAPI(props, ({ orderID }) => `/booking-order/${orderID}/invoice`)
    .get()
    .json<OrderInvoice>()

export const createOrderInvoice = (props: MaybeRef<OrderInvoicePayload>) =>
  useAdminAPI(
    props,
    ({ orderID }) => `/booking-order/${orderID}/invoice`,
    { immediate: true },
  )
    .post('', 'application/json')
    .json<OrderInvoiceCreateResponse>()

export const cancelOrderInvoice = (props: MaybeRef<OrderInvoicePayload>) =>
  useAdminAPI(
    props,
    ({ orderID }) => `/booking-order/${orderID}/invoice/cancel`,
    { immediate: true },
  )
    .post('', 'application/json')
    .json<BaseResponse>()

export const sendOrderInvoice = (props: MaybeRef<OrderInvoicePayload>) =>
  useAdminAPI(
    props,
    ({ orderID }) => `/booking-order/${orderID}/invoice/send`,
    { immediate: true },
  )
    .post('', 'application/json')
    .json<OrderInvoiceCreateResponse>()

const getDocumentFileInfo = (props: MaybeRef<OrderInvoicePayload>) =>
  useAdminAPI(
    props,
    ({ orderID }) => `/booking-order/${orderID}/invoice/file`,
    { immediate: true },
  )
    .get()
    .json<FileResponse>()

export const downloadDocument = async (props: MaybeRef<OrderInvoicePayload>): Promise<void> => {
  const { data: fileInfo } = await getDocumentFileInfo(props)
  if (!fileInfo.value) {
    return
  }
  await downloadFile(fileInfo.value.url, fileInfo.value.name)
}
