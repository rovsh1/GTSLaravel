import { MaybeRef } from '@vueuse/core'

import { useAdminAPI } from '~api'
import { BookingID } from '~api/booking/index'

export type StatusID = number

export interface BookingStatusesResponse {
  id: StatusID
  name: string
  key: string
}

export interface BookingAvailableActionsResponse {
  statuses: BookingStatusesResponse[]
  isRequestable: boolean
  canSendBookingRequest: boolean
  canSendCancellationRequest: boolean
  canSendChangeRequest: boolean
  canSendVoucher: boolean
  canEditExternalNumber: boolean
}

export interface BookingAvailableStatusesPayload {
  bookingID: BookingID
}

export const useBookingStatusesAPI = (props?: any) =>
  useAdminAPI(props, () => '/hotel-booking/status/list')
    .get()
    .json<BookingStatusesResponse[]>()

export const useBookingAvailableActionsAPI = (props: MaybeRef<BookingAvailableStatusesPayload | null>) =>
  useAdminAPI(props, ({ bookingID }) =>
    `/hotel-booking/${bookingID}/actions/available`)
    .get()
    .json<BookingAvailableActionsResponse>()
