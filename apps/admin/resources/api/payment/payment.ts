import { computed } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { BaseResponse, useAdminAPI } from '~api'

import { getNullableRef } from '~lib/vue'

export type PaymentCurrency = {
  id: number
  value: string
  name: string
}

export type PaymentOrderLanding = {
  orderId: number
  sum: number
}

export type PaymentInfo = {
  id: number
  clientId: number
  totalAmount: PaymentPrice
  payedAmount: PaymentPrice
  remainingAmount: PaymentPrice
  landings: PaymentOrderLanding[]
}

export type PaymentPrice = {
  currency: PaymentCurrency
  value: number
}

export type PaymentOrder = {
  id: number
  clientId: number
  clientPrice: PaymentPrice
  payedAmount: PaymentPrice
  remainingAmount: PaymentPrice
}

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

export const useGetPaymentAPI = (props: MaybeRef<PaymentPayload | null>) =>
  useAdminAPI(props, ({ paymentID }) => `/payment/${paymentID}`)
    .get()
    .json<PaymentInfo>()

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
