import { computed } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { BaseResponse, useAdminAPI } from '~api'
import { BaseBooking, BookingID } from '~api/booking/models'

import { getNullableRef } from '~lib/vue'

export type ServiceType = {
  id: number
  name: string
}

export interface BookingDetailsType {
  id: number
  display_name: string
  system_name: string
}

export type Booking = {
  details?: any
  serviceType: ServiceType
} & BaseBooking

export interface GetBookingPayload {
  bookingID: BookingID
}

export interface UpdateBookingStatusPayload {
  bookingID: BookingID
  status: number
  notConfirmedReason?: string
  cancelFeeAmount?: number
  clientCancelFeeAmount?: number
}

export interface UpdateExternalNumberPayload {
  bookingID: BookingID
  type: number
  number?: string | null
}

export interface UpdateBookingStatusResponse {
  isNotConfirmedReasonRequired: boolean
  isCancelFeeAmountRequired: boolean
}

export const useGetBookingAPI = (props: MaybeRef<GetBookingPayload>) =>
  useAdminAPI(props, ({ bookingID }) => `/booking/${bookingID}/get`)
    .get()
    .json<Booking>()

export const updateBookingStatus = (props: MaybeRef<UpdateBookingStatusPayload>) =>
  useAdminAPI(
    props,
    ({ bookingID }) => `/booking/${bookingID}/status/update`,
    { immediate: true },
  )
    .put(computed<string>(() => JSON.stringify(
      getNullableRef<UpdateBookingStatusPayload, any>(
        props,
        (payload: UpdateBookingStatusPayload): any => ({
          status: payload.status,
          not_confirmed_reason: payload.notConfirmedReason,
          net_penalty: payload.cancelFeeAmount,
          gross_penalty: payload.clientCancelFeeAmount,
        }),
      ),
    )), 'application/json')
    .json<UpdateBookingStatusResponse>()

export const updateExternalNumber = (props: MaybeRef<UpdateExternalNumberPayload>) =>
  useAdminAPI(
    props,
    ({ bookingID }) => `/booking/${bookingID}/external/number`,
    { immediate: true },
  )
    .put(computed<string>(() => JSON.stringify(
      getNullableRef<UpdateExternalNumberPayload, any>(
        props,
        (payload: UpdateExternalNumberPayload): any => ({
          type: payload.type,
          number: payload.number,
        }),
      ),
    )), 'application/json')
    .json<BaseResponse>()
