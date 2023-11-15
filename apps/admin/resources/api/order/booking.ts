import { useAdminAPI } from '~api'
import { BaseBooking } from '~api/booking/models'

type ServiceType = {
  id: number
  name: string
}

export type OrderBooking = BaseBooking & {
  serviceType: ServiceType
}

export const useGetOrderBookingsAPI = ({ orderId }: { orderId: number }) =>
  useAdminAPI({ orderId }, () => `/booking-order/${orderId}/bookings`)
    .get()
    .json<OrderBooking[]>()
