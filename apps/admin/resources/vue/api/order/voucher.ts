import { MaybeRef } from '@vueuse/core'

import { BaseResponse, DateResponse, useAdminAPI } from '~api'
import { FileResponse } from '~api/hotel/images'
import { OrderID } from '~api/order/models'

export interface OrderVoucherPayload {
  orderID: OrderID
}

export type OrderVoucher = {
  createdAt: DateResponse
  sendAt: DateResponse | null
  file: FileResponse
  wordFile: FileResponse | null
}

export interface OrderVoucherSendResponse extends BaseResponse {

}

export const createOrderVoucher = (props: MaybeRef<OrderVoucherPayload>) =>
  useAdminAPI(
    props,
    ({ orderID }) => `/booking-order/${orderID}/voucher`,
    { immediate: true },
  )
    .post('', 'application/json')
    .json<OrderVoucher>()

export const sendOrderVoucher = (props: MaybeRef<OrderVoucherPayload>) =>
  useAdminAPI(
    props,
    ({ orderID }) => `/booking-order/${orderID}/voucher/send`,
    { immediate: true },
  )
    .post('', 'application/json')
    .json<OrderVoucherSendResponse>()
