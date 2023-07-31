import { MaybeRef } from '@vueuse/core'

import { DateResponse, useAdminAPI } from '~api'

export type StatusID = number

export interface BookingStatusResponse {
  id: StatusID
  name: string
  color: string
}

export interface BookingAvailableActionsResponse {
  statuses: BookingStatusResponse[]
  isEditable: boolean
  isRequestable: boolean
  canSendBookingRequest: boolean
  canSendCancellationRequest: boolean
  canSendChangeRequest: boolean
  canSendVoucher: boolean
  canEditExternalNumber: boolean
  canChangeRoomPrice: boolean
  canCopy: boolean
}

export interface BookingStatusHistoryData extends Record<string, any> {
  reason?: string
  cancelFeeAmount?: number
}

export interface BookingStatusHistoryResponse {
  event: string
  color: string
  payload: BookingStatusHistoryData | null
  administratorName: string
  source: string
  dateCreate: DateResponse
}

export interface BookingAvailableStatusesPayload {
  bookingID: number
}

export interface BookingStatusHistoryPayload {
  bookingID: number
}

export const useBookingStatusesAPI = (props?: any) =>
  useAdminAPI(props, () => '/hotel-booking/status/list')
    .get()
    .json<BookingStatusResponse[]>()

export const useBookingAvailableActionsAPI = (props: MaybeRef<BookingAvailableStatusesPayload | null>) =>
  useAdminAPI(props, ({ bookingID }) =>
    `/hotel-booking/${bookingID}/actions/available`)
    .get()
    .json<BookingAvailableActionsResponse>()

export const useBookingStatusHistoryAPI = (props: MaybeRef<BookingStatusHistoryPayload | null>) =>
  useAdminAPI(props, ({ bookingID }) => `/hotel-booking/${bookingID}/status/history`)
    .get()
    .json<BookingStatusHistoryResponse[]>()
