import { computed } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { BaseResponse, useAdminAPI } from '~api'
import { BookingID } from '~api/booking/index'

import { getNullableRef } from '~lib/vue'

export interface BookingAddRoomPayload {
  bookingID: BookingID
  id: number
  status: number
  roomCount: number
  residentType: number
  discount?: number
  rateId: number
  note?: string
  earlyCheckIn?: string
  lateCheckOut?: string
}

export interface BookingUpdateRoomPayload extends BookingAddRoomPayload {
  roomIndex: number
}

export interface DeleteBookingRoomPayload {
  bookingID: BookingID
  roomIndex: number
}

export interface BookingAddRoomGuestPayload {
  bookingID: BookingID
  roomIndex: number
  countryId: number
  fullName: string
  gender: number
}

export interface BookingUpdateRoomGuestPayload extends BookingAddRoomGuestPayload {
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
          room_count: payload.roomCount,
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
          room_index: payload.roomIndex,
          room_id: payload.id,
          status: payload.status,
          room_count: payload.roomCount,
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
        (payload: DeleteBookingRoomPayload): any => ({ room_index: payload.roomIndex }),
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
          room_index: payload.roomIndex,
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
          room_index: payload.roomIndex,
          guest_index: payload.guestIndex,
        }),
      ),
    )), 'application/json')
    .json<BaseResponse>()
