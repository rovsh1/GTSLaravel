import { MaybeRef } from '@vueuse/core'

import { DateResponse, useAdminAPI } from '~api'
import { BookingID } from '~api/booking/index'

export type StatusID = number

export interface BookingStatusesResponse {
  id: StatusID
  name: string
  key: string
}

export interface BookingAvailableActionsResponse {
  statuses: BookingStatusesResponse[]
  isEditable: boolean
  isRequestable: boolean
  canSendBookingRequest: boolean
  canSendCancellationRequest: boolean
  canSendChangeRequest: boolean
  canSendVoucher: boolean
  canEditExternalNumber: boolean
}

export interface BookingStatusHistoryResponse {
  event: string
  userId: number
  source: number
  dateCreate: DateResponse
}

export interface BookingAvailableStatusesPayload {
  bookingID: BookingID
}

export interface BookingStatusHistoryPayload {
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

export const useBookingStatusHistoryAPI = (props: MaybeRef<BookingStatusHistoryPayload | null>) =>
  useAdminAPI(props, ({ bookingID }) => `/hotel-booking/${bookingID}/status/history`)
    .get()
    .json<BookingStatusHistoryResponse[]>()
