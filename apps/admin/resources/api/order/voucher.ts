import { MaybeRef } from '@vueuse/core'

import { DateResponse, useAdminAPI } from '~api'
import { FileResponse } from '~api/hotel/images'
import { OrderID } from '~api/order/models'

import { downloadFile } from '~lib/download-file'

export interface OrderVoucherPayload {
  orderID: OrderID
}

export interface OrderVoucher {
  id: number
  createdAt: DateResponse
}

export interface OrderVoucherCreateResponse {
  isExternalNumberRequired: boolean
}

export const useOrderVoucherGetAPI = (props: MaybeRef<OrderVoucherPayload>) =>
  useAdminAPI(props, ({ orderID }) => `/booking-order/${orderID}/voucher`)
    .get()
    .json<OrderVoucher>()

export const createOrderVoucher = (props: MaybeRef<OrderVoucherPayload>) =>
  useAdminAPI(
    props,
    ({ orderID }) => `/booking-order/${orderID}/voucher`,
    { immediate: true },
  )
    .post('', 'application/json')
    .json<OrderVoucherCreateResponse>()

export const sendOrderVoucher = (props: MaybeRef<OrderVoucherPayload>) =>
  useAdminAPI(
    props,
    ({ orderID }) => `/booking-order/${orderID}/voucher/send`,
    { immediate: true },
  )
    .post('', 'application/json')
    .json<OrderVoucherCreateResponse>()

const getDocumentFileInfo = (props: MaybeRef<OrderVoucherPayload>) =>
  useAdminAPI(
    props,
    ({ orderID }) => `/booking-order/${orderID}/voucher/file`,
    { immediate: true },
  )
    .get()
    .json<FileResponse>()

export const downloadDocument = async (props: MaybeRef<OrderVoucherPayload>): Promise<void> => {
  const { data: fileInfo } = await getDocumentFileInfo(props)
  if (!fileInfo.value) {
    return
  }
  await downloadFile(fileInfo.value.url, fileInfo.value.name)
}
