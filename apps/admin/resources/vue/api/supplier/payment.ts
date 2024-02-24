import { computed } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { getNullableRef } from '~resources/vue/vue'

import { BaseResponse, useAdminAPI } from '~api'

export type PaymentCurrency = {
  id: number
  value: string
  name: string
}

export type PaymentBookingLanding = {
  bookingId: number
  sum: number
}

export type PaymentInfo = {
  id: number
  supplierId: number
  totalAmount: PaymentPrice
  payedAmount: PaymentPrice
  remainingAmount: PaymentPrice
  landings: PaymentBookingLanding[]
}

export type PaymentPrice = {
  currency: PaymentCurrency
  value: number
}

export type PaymentBooking = {
  id: number
  supplierId: number
  supplierPrice: PaymentPrice
  supplierPenalty: PaymentPrice | null
  payedAmount: PaymentPrice
  remainingAmount: PaymentPrice
}

export type PaymentPayload = {
  paymentID: number
}

export type PaymentBookingPayload = {
  id: number
  sum: number
}

export type PaymentBookingsPayload = PaymentPayload & {
  bookings: PaymentBookingPayload[]
}

export const useGetPaymentAPI = (props: MaybeRef<PaymentPayload | null>) =>
  useAdminAPI(props, ({ paymentID }) => `/supplier-payment/${paymentID}`)
    .get()
    .json<PaymentInfo>()

export const usePaymentWaitingBookingsListAPI = (props: MaybeRef<PaymentPayload | null>) =>
  useAdminAPI(props, ({ paymentID }) => `/supplier-payment/${paymentID}/waiting-bookings`)
    .get()
    .json<PaymentBooking[]>()

export const usePaymentBookingsListAPI = (props: MaybeRef<PaymentPayload | null>) =>
  useAdminAPI(props, ({ paymentID }) => `/supplier-payment/${paymentID}/bookings`)
    .get()
    .json<PaymentBooking[]>()

export const bookingsLend = (props: MaybeRef<PaymentBookingsPayload>) =>
  useAdminAPI(
    props,
    ({ paymentID }) => `/supplier-payment/${paymentID}/bookings/lend`,
    { immediate: true },
  )
    .post(computed<string>(() => JSON.stringify(
      getNullableRef<PaymentBookingsPayload, any>(props, (payload: PaymentBookingsPayload): any => ({
        bookings: payload.bookings,
      })),
    )), 'application/json')
    .json<BaseResponse>()
