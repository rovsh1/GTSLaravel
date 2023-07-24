import { computed } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { BaseResponse, DateResponse, useAdminAPI } from '~api'
import { BookingID } from '~api/booking/index'

import { getNullableRef } from '~lib/vue'

export interface BookingRequestPayload {
  bookingID: BookingID
}

export interface BookingRequest {
  id: number
  type: number
  dateCreate: DateResponse
}

export const useBookingRequestListAPI = (props: MaybeRef<BookingRequestPayload>) =>
  useAdminAPI(props, ({ bookingID }) => `/hotel-booking/${bookingID}/request/list`)
    .get()
    .json<BookingRequest[]>()

export const sendBookingRequest = (props: MaybeRef<BookingRequestPayload>) =>
  useAdminAPI(
    props,
    ({ bookingID }) => `/hotel-booking/${bookingID}/request`,
    { immediate: true },
  )
    .post(computed<string>(() => JSON.stringify(
      getNullableRef<BookingRequestPayload, any>(
        props,
        (): any => null,
      ),
    )), 'application/json')
    .json<BaseResponse>()
