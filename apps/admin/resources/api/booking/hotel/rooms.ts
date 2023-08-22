import { computed } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { BaseResponse, useAdminAPI } from '~api'
import { BookingID } from '~api/booking/models'
import { MarkupCondition } from '~api/hotel/markup-settings'

import { getNullableRef } from '~lib/vue'

export interface BookingAddRoomPayload {
  bookingID: BookingID
  id: number
  status: number
  residentType: number
  discount?: number
  rateId: number
  note?: string
  earlyCheckIn?: MarkupCondition
  lateCheckOut?: MarkupCondition
}

export interface BookingUpdateRoomPayload extends BookingAddRoomPayload {
  roomBookingId: number
}

export interface DeleteBookingRoomPayload {
  bookingID: BookingID
  roomBookingId: number
}

export interface BookingAddRoomGuestPayload {
  bookingID: BookingID
  roomBookingId: number
  countryId: number
  fullName: string
  gender: number
  isAdult: boolean
  age?: number | null
}

export interface BookingUpdateRoomGuestPayload extends BookingAddRoomGuestPayload {
  guestIndex: number
}

export interface BookingDeleteRoomGuestPayload {
  bookingID: BookingID
  roomBookingId: number
  guestIndex: number
}

export const addRoomToBooking = (props: MaybeRef<BookingAddRoomPayload | null>) =>
  useAdminAPI(
    props,
    ({ bookingID }) => `/hotel-booking/${bookingID}/rooms/add`,
    { immediate: true },
  )
    .post(computed<string>(() => JSON.stringify(
      getNullableRef<BookingAddRoomPayload, any>(
        props,
        (payload: BookingAddRoomPayload): any => ({
          room_id: payload.id,
          status: payload.status,
          is_resident: Boolean(Number(payload.residentType)),
          discount: payload.discount,
          rate_id: payload.rateId,
          note: payload.note,
          late_check_out: payload.lateCheckOut,
          early_check_in: payload.earlyCheckIn,
        }),
      ),
    )), 'application/json')
    .json<BaseResponse>()

export const updateBookingRoom = (props: MaybeRef<BookingUpdateRoomPayload | null>) =>
  useAdminAPI(
    props,
    ({ bookingID }) => `/hotel-booking/${bookingID}/rooms`,
    { immediate: true },
  )
    .put(computed<string>(() => JSON.stringify(
      getNullableRef<BookingUpdateRoomPayload, any>(
        props,
        (payload: BookingUpdateRoomPayload): any => ({
          room_booking_id: payload.roomBookingId,
          room_id: payload.id,
          status: payload.status,
          is_resident: Boolean(Number(payload.residentType)),
          discount: payload.discount,
          rate_id: payload.rateId,
          note: payload.note,
          late_check_out: payload.lateCheckOut,
          early_check_in: payload.earlyCheckIn,
        }),
      ),
    )), 'application/json')
    .json<BaseResponse>()

export const deleteBookingRoom = (props: MaybeRef<DeleteBookingRoomPayload | null>) =>
  useAdminAPI(
    props,
    ({ bookingID }) => `/hotel-booking/${bookingID}/rooms`,
    { immediate: true },
  )
    .delete(computed<string>(() => JSON.stringify(
      getNullableRef<DeleteBookingRoomPayload, any>(
        props,
        (payload: DeleteBookingRoomPayload): any => ({ room_booking_id: payload.roomBookingId }),
      ),
    )), 'application/json')
    .json<BaseResponse>()

export const addGuestToBooking = (props: MaybeRef<BookingAddRoomGuestPayload | null>) =>
  useAdminAPI(
    props,
    ({ bookingID }) => `/hotel-booking/${bookingID}/rooms/guests/add`,
    { immediate: true },
  )
    .post(computed<string>(() => JSON.stringify(
      getNullableRef<BookingAddRoomGuestPayload, any>(
        props,
        (payload: BookingAddRoomGuestPayload): any => ({
          full_name: payload.fullName,
          country_id: payload.countryId,
          gender: payload.gender,
          room_booking_id: payload.roomBookingId,
          is_adult: payload.isAdult,
          age: payload.age,
        }),
      ),
    )), 'application/json')
    .json<BaseResponse>()

export const updateBookingGuest = (props: MaybeRef<BookingUpdateRoomGuestPayload | null>) =>
  useAdminAPI(
    props,
    ({ bookingID }) => `/hotel-booking/${bookingID}/rooms/guests`,
    { immediate: true },
  )
    .put(computed<string>(() => JSON.stringify(
      getNullableRef<BookingUpdateRoomGuestPayload, any>(
        props,
        (payload: BookingUpdateRoomGuestPayload): any => ({
          full_name: payload.fullName,
          country_id: payload.countryId,
          gender: payload.gender,
          room_booking_id: payload.roomBookingId,
          guest_index: payload.guestIndex,
          is_adult: payload.isAdult,
          age: payload.age,
        }),
      ),
    )), 'application/json')
    .json<BaseResponse>()

export const deleteBookingGuest = (props: MaybeRef<BookingDeleteRoomGuestPayload | null>) =>
  useAdminAPI(
    props,
    ({ bookingID }) => `/hotel-booking/${bookingID}/rooms/guests`,
    { immediate: true },
  )
    .delete(computed<string>(() => JSON.stringify(
      getNullableRef<BookingDeleteRoomGuestPayload, any>(props, (payload: BookingDeleteRoomGuestPayload): any => ({ room_booking_id: payload.roomBookingId, guest_index: payload.guestIndex })),
    )), 'application/json')
    .json<BaseResponse>()
