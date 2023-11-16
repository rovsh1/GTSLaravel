import { DateResponse, useAdminAPI } from '~api'
import { BaseBooking } from '~api/booking/models'

type ServiceType = {
  id: number
  name: string
}

type ServiceInfo = {
  id: number
  name: string
}

type BookingPeriod = {
  dateFrom: DateResponse
  dateTo: DateResponse
}

export type OrderBooking = BaseBooking & {
  serviceType: ServiceType
  serviceInfo: ServiceInfo
  bookingPeriod: BookingPeriod | null
}

export const useGetOrderBookingsAPI = ({ orderId }: { orderId: number }) =>
  useAdminAPI({ orderId }, () => `/booking-order/${orderId}/bookings`)
    .get()
    .json<OrderBooking[]>()
