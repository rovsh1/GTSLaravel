import { computed } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { BaseResponse, useAdminAPI } from '~api'

import { getNullableRef } from '~lib/vue'

export type BookingID = number

export interface Booking {
  id: BookingID
  orderId: number
  status: number
  type: number
  note: string
  source: number
  creatorId: number
}

export interface GetBookingPayload {
  bookingID: BookingID
}

export interface UpdateBookingStatusPayload {
  bookingID: BookingID
  status: number
}

export interface UpdateExternalNumberPayload {
  bookingID: BookingID
  type: number
  number?: string | null
}

export const useGetBookingAPI = (props: MaybeRef<GetBookingPayload>) =>
  useAdminAPI(props, ({ bookingID }) => `/hotel-booking/${bookingID}/get`)
    .get()
    .json<Booking>()

export const updateBookingStatus = (props: MaybeRef<UpdateBookingStatusPayload>) =>
  useAdminAPI(
    props,
    ({ bookingID }) => `/hotel-booking/${bookingID}/status/update`,
    { immediate: true },
  )
    .put(computed<string>(() => JSON.stringify(
      getNullableRef<UpdateBookingStatusPayload, any>(
        props,
        (payload: UpdateBookingStatusPayload): any => ({
          status: payload.status,
        }),
      ),
    )), 'application/json')
    .json<BaseResponse>()

export const updateExternalNumber = (props: MaybeRef<UpdateExternalNumberPayload>) =>
  useAdminAPI(
    props,
    ({ bookingID }) => `/hotel-booking/${bookingID}/external/number`,
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
