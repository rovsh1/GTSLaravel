import { computed } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { DateResponse, useAdminAPI } from '~api'

import { getNullableRef } from '~lib/vue'

export interface OrderVoucherPayload {
  orderID: number
}

export interface OrderVoucher {
  id: number
  dateCreate: DateResponse
}

export interface OrderVoucherSendResponse {
  isExternalNumberRequired: boolean
}

export const useOrderVoucherListAPI = (props: MaybeRef<OrderVoucherPayload>) =>
  useAdminAPI(props, ({ orderID }) => `/booking-order/${orderID}/voucher/list`)
    .get()
    .json<OrderVoucher[]>()

export const sendOrderVoucher = (props: MaybeRef<OrderVoucherPayload>) =>
  useAdminAPI(
    props,
    ({ orderID }) => `/booking-order/${orderID}/voucher`,
    { immediate: true },
  )
    .post(computed<string>(() => JSON.stringify(
      getNullableRef<OrderVoucherPayload, any>(
        props,
        (): any => null,
      ),
    )), 'application/json')
    .json<OrderVoucherSendResponse>()
