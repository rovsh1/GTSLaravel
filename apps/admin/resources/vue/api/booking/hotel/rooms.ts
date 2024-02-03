import { computed } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { getNullableRef } from '~resources/vue/vue'

import { BaseResponse, useAdminAPI } from '~api'
import { BookingID } from '~api/booking/models'
import { MarkupCondition } from '~api/hotel/markup-settings'
import { HotelRoomResponse } from '~api/hotel/room'

export interface BookingAddRoomPayload {
  bookingID: BookingID
  id: number
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
  guestId: number
}

export interface BookingDeleteRoomGuestPayload {
  bookingID: BookingID
  roomBookingId: number
  guestId: number
}

export interface GetAvailableRoomsPayload {
  bookingID: BookingID
}

export const useGetAvailableRoomsAPI = (props: MaybeRef<GetAvailableRoomsPayload>) =>
  useAdminAPI(props, ({ bookingID }) => `/hotel-booking/${bookingID}/rooms/available`)
    .get()
    .json<HotelRoomResponse[]>()

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
      getNullableRef<BookingDeleteRoomGuestPayload, any>(props, (payload: BookingDeleteRoomGuestPayload): any => ({ room_booking_id: payload.roomBookingId, guest_id: payload.guestId })),
    )), 'application/json')
    .json<BaseResponse>()

export const deleteBookingGuest = (props: MaybeRef<BookingDeleteRoomGuestPayload | null>) =>
  useAdminAPI(
    props,
    ({ bookingID }) => `/hotel-booking/${bookingID}/rooms/guests`,
    { immediate: true },
  )
    .delete(computed<string>(() => JSON.stringify(
      getNullableRef<BookingDeleteRoomGuestPayload, any>(props, (payload: BookingDeleteRoomGuestPayload): any => ({ room_booking_id: payload.roomBookingId, guest_id: payload.guestId })),
    )), 'application/json')
    .json<BaseResponse>()
