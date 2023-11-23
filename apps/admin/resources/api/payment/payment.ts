import { computed } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { BaseResponse, useAdminAPI } from '~api'

import { getNullableRef } from '~lib/vue'

export type PaymentPayload = {
  paymentID: number
}

export type PaymentOrderPayload = {
  id: number
  sum: number
}

export type PaymentOrdersPayload = PaymentPayload & {
  orders: PaymentOrderPayload[]
}

export interface PaymentOrder {
  id: number
  name: string
}

export const usePaymentWaitingOrdersListAPI = (props: MaybeRef<PaymentPayload | null>) =>
  useAdminAPI(props, ({ paymentID }) => `/payment/${paymentID}/waiting-orders`)
    .get()
    .json<PaymentOrder[]>()

export const usePaymentOrdersListAPI = (props: MaybeRef<PaymentPayload | null>) =>
  useAdminAPI(props, ({ paymentID }) => `/payment/${paymentID}/orders`)
    .get()
    .json<PaymentOrder[]>()

export const ordersLend = (props: MaybeRef<PaymentOrdersPayload>) =>
  useAdminAPI(
    props,
    ({ paymentID }) => `/payment/${paymentID}/orders/lend`,
    { immediate: true },
  )
    .post(computed<string>(() => JSON.stringify(
      getNullableRef<PaymentOrdersPayload, any>(props, (payload: PaymentOrdersPayload): any => ({
        orders: payload.orders,
      })),
    )), 'application/json')
    .json<BaseResponse>()
