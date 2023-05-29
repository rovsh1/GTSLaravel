import { computed } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { useAdminAPI } from '~api'

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

export interface UpdateBookingStatusPayload extends GetBookingPayload {
  status: number
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
    .json<Booking>()
