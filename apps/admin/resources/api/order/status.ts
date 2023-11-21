import { MaybeRef } from '@vueuse/core'

import { DateResponse, useAdminAPI } from '~api'
import { OrderStatusResponse } from '~api/order/models'

export interface OrderAvailableActionsResponse {
  statuses: OrderStatusResponse[]
  isEditable: boolean
  isRequestable: boolean
  canSendVoucher: boolean
  canCreateInvoice: boolean
  canSendInvoice: boolean
  canCancelInvoice: boolean
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

export interface OrderAvailableStatusesPayload {
  orderID: number
}

export interface BookingStatusHistoryPayload {
  bookingID: number
}

export const useBookingStatusesAPI = (props?: any) =>
  useAdminAPI(props, () => '/booking-order/status/list')
    .get()
    .json<OrderStatusResponse[]>()

export const useBookingAvailableActionsAPI = (props: MaybeRef<OrderAvailableStatusesPayload | null>) =>
  useAdminAPI(props, ({ orderID }) =>
    `/booking-order/${orderID}/actions/available`)
    .get()
    .json<OrderAvailableActionsResponse>()
