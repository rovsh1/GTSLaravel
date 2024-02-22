import { MaybeRef } from '@vueuse/core'

import { DateResponse, useAdminAPI } from '~api'
import { BookingID } from '~api/booking/models'

export interface BookingRequestPayload {
  bookingID: BookingID
}

export interface BookingRequest {
  id: number
  type: number
  dateCreate: DateResponse
}

export const useBookingRequestListAPI = (props: MaybeRef<BookingRequestPayload>) =>
  useAdminAPI(props, ({ bookingID }) => `/booking/${bookingID}/request/list`)
    .get()
    .json<BookingRequest[]>()
