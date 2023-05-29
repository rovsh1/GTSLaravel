import { MaybeRef } from '@vueuse/core'

import { useAdminAPI } from '~api'
import { BookingID } from '~api/booking/index'
import { Percent } from '~api/hotel/markup-settings'

export interface BookingHotelDetailsPayload {
  bookingID: BookingID
}

export interface HotelBookingGuest {
  id: number
  fullName: string
  countryId: number
  gender: number
}

export interface HotelBookingDetailsRoom {
  id: number
  rateId: number
  status: number
  roomCount: number
  guests: HotelBookingGuest[]
  guestNote: string
  discount: Percent
  isResident: boolean
}

export interface HotelBookingDetails {
  id: number
  rooms: HotelBookingDetailsRoom[]
}

export const useBookingHotelDetailsAPI = (props: MaybeRef<BookingHotelDetailsPayload | null>) =>
  useAdminAPI(props, ({ bookingID }) =>
    `/hotel-booking/${bookingID}/details`)
    .get()
    .json<HotelBookingDetails>()
