import { MaybeRef } from '@vueuse/core'

import { DateResponse, useAdminAPI } from '~api'
import { FileResponse } from '~api/hotel/images'
import { OrderID } from '~api/order/models'

export interface OrderVoucherPayload {
  orderID: OrderID
}

export interface OrderVoucher {
  createdAt: DateResponse
  file: FileResponse
}

export interface OrderVoucherCreateResponse {
  isExternalNumberRequired: boolean
}

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
