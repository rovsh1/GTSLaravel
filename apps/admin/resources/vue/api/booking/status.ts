import { MaybeRef } from '@vueuse/core'

import { DateResponse, useAdminAPI } from '~api'
import { StatusID } from '~api/booking/models'

export interface StatusSettings {
  id: StatusID
  name: string
  color: string
}

export interface BookingAvailableActionsResponse {
  statuses: StatusSettings[]
  isEditable: boolean
  isRequestable: boolean
  canSendBookingRequest: boolean
  canSendCancellationRequest: boolean
  canSendChangeRequest: boolean
  canEditExternalNumber: boolean
  canChangeRoomPrice: boolean
  canCopy: boolean
  canEditSupplierPrice: boolean
  canEditClientPrice: boolean
  canChangeCarBidPrice: boolean
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
  createdAt: DateResponse
}

export interface BookingAvailableStatusesPayload {
  bookingID: number
}

export interface BookingStatusHistoryPayload {
  bookingID: number
}

export const useBookingStatusesAPI = (props?: any) =>
  useAdminAPI(props, () => '/service-booking/status/list')
    .get()
    .json<StatusSettings[]>()

export const useBookingAvailableActionsAPI = (props: MaybeRef<BookingAvailableStatusesPayload | null>) =>
  useAdminAPI(props, ({ bookingID }) =>
    `/service-booking/${bookingID}/actions/available`)
    .get()
    .json<BookingAvailableActionsResponse>()

export const useBookingStatusHistoryAPI = (props: MaybeRef<BookingStatusHistoryPayload | null>) =>
  useAdminAPI(props, ({ bookingID }) => `/service-booking/${bookingID}/status/history`)
    .get()
    .json<BookingStatusHistoryResponse[]>()
