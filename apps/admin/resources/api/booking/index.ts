import { MaybeRef } from '@vueuse/core'

import { useAdminAPI } from '~api'

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

export const useBookingAPI = (props: MaybeRef<GetBookingPayload>) =>
  useAdminAPI(props, ({ bookingID }) => `/hotel-booking/${bookingID}/get`)
    .get()
    .json<Booking>()
