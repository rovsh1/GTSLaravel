import { computed } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { BaseResponse, useAdminAPI } from '~api'
import { BookingID } from '~api/booking/models'

import { getNullableRef } from '~lib/vue'

export interface BookingDeleteGuestPayload {
  bookingID: BookingID
  guestId: number
}

export const deleteBookingGuest = (props: MaybeRef<BookingDeleteGuestPayload | null>) =>
  useAdminAPI(
    props,
    ({ bookingID }) => `/airport-booking/${bookingID}/guests`,
    { immediate: true },
  )
    .delete(computed<string>(() => JSON.stringify(
      getNullableRef<BookingDeleteGuestPayload, any>(props, (payload: BookingDeleteGuestPayload): any => ({ guest_id: payload.guestId })),
    )), 'application/json')
    .json<BaseResponse>()

export const addBookingGuest = (props: MaybeRef<BookingDeleteGuestPayload | null>) =>
  useAdminAPI(
    props,
    ({ bookingID }) => `/airport-booking/${bookingID}/guests/add`,
    { immediate: true },
  )
    .post(computed<string>(() => JSON.stringify(
      getNullableRef<BookingDeleteGuestPayload, any>(props, (payload: BookingDeleteGuestPayload): any => ({ guest_id: payload.guestId })),
    )), 'application/json')
    .json<BaseResponse>()
