import { computed } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { getNullableRef } from '~resources/vue/vue'

import { BaseResponse, useAdminAPI } from '~api'
import { BookingID } from '~api/booking/models'

export interface BookingDeleteGuestPayload {
  bookingID: BookingID
  guestId: number
}

export type BookingAddCarGuestPayload = {
  bookingID: BookingID
  carBidId: number
  guestId: number
}

export type BookingDeleteCarGuestPayload = BookingAddCarGuestPayload

export const deleteBookingGuest = (props: MaybeRef<BookingDeleteGuestPayload | null>) =>
  useAdminAPI(
    props,
    ({ bookingID }) => `/service-booking/${bookingID}/guests`,
    { immediate: true },
  )
    .delete(computed<string>(() => JSON.stringify(
      getNullableRef<BookingDeleteGuestPayload, any>(props, (payload: BookingDeleteGuestPayload): any => ({ guest_id: payload.guestId })),
    )), 'application/json')
    .json<BaseResponse>()

export const addBookingGuest = (props: MaybeRef<BookingDeleteGuestPayload | null>) =>
  useAdminAPI(
    props,
    ({ bookingID }) => `/service-booking/${bookingID}/guests/add`,
    { immediate: true },
  )
    .post(computed<string>(() => JSON.stringify(
      getNullableRef<BookingDeleteGuestPayload, any>(props, (payload: BookingDeleteGuestPayload): any => ({ guest_id: payload.guestId })),
    )), 'application/json')
    .json<BaseResponse>()

export const addGuestToCar = (props: MaybeRef<BookingAddCarGuestPayload | null>) =>
  useAdminAPI(
    props,
    ({ bookingID, carBidId }) => `/service-booking/${bookingID}/cars/${carBidId}/guests/add`,
    { immediate: true },
  )
    .post(computed<string>(() => JSON.stringify(
      getNullableRef<BookingAddCarGuestPayload, any>(props, (payload: BookingAddCarGuestPayload): any => ({ guest_id: payload.guestId })),
    )), 'application/json')
    .json<BaseResponse>()

export const deleteGuestFromCar = (props: MaybeRef<BookingDeleteCarGuestPayload | null>) =>
  useAdminAPI(
    props,
    ({ bookingID, carBidId }) => `/service-booking/${bookingID}/cars/${carBidId}/guests`,
    { immediate: true },
  )
    .delete(computed<string>(() => JSON.stringify(
      getNullableRef<BookingDeleteCarGuestPayload, any>(props, (payload: BookingDeleteCarGuestPayload): any => ({ guest_id: payload.guestId })),
    )), 'application/json')
    .json<BaseResponse>()
