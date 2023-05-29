import { MaybeRef } from '@vueuse/core'

import { useAdminAPI } from '~api'
import { BookingID } from '~api/booking/index'

export type StatusID = number

export interface BookingStatusesResponse {
  id: StatusID
  name: string
}

export interface BookingAvailableStatusesPayload {
  bookingID: BookingID
}

export const useBookingStatusesAPI = (props?: any) =>
  useAdminAPI(props, () => '/hotel-booking/status/list')
    .get()
    .json<BookingStatusesResponse[]>()

export const useBookingAvailableStatusesAPI = (props: MaybeRef<BookingAvailableStatusesPayload | null>) =>
  useAdminAPI(props, ({ bookingID }) =>
    `/hotel-booking/${bookingID}/status/available`)
    .get()
    .json<BookingStatusesResponse[]>()
