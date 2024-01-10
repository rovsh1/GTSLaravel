import { useAdminAPI } from '~api'

export type Guest = {
  id: number
  orderId: number
  countryId: number
  fullName: string
  gender: number
  isAdult: boolean
  age?: number | null
}

export const useGetOrderGuestsAPI = ({ bookingId }: { bookingId: number }) =>
  useAdminAPI({ bookingId }, () => `/booking/${bookingId}/order/guests`)
    .get()
    .json<Guest[]>()
