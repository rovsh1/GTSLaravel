import { DateResponse, useAdminAPI } from '~api'

export type HotelID = number

export type HotelResponse = {
  id: HotelID
  name: string
  city_id: number
  city_distance: number
  city_name: string
  address: string
  address_lat: number
  address_lon: number
  country_name: string
  zipcode: string
  rating: number | null
  status: number
  type_id: number
  type_name: string
  // 0 = Hidden, 1 = Public, 2 = B2B
  visibility: 0 | 1 | 2
  created_at: DateResponse
  updated_at: DateResponse
  deleted_at: DateResponse | null
}

export const useHotelGetAPI = () =>
  useAdminAPI({ }, () =>
    '/hotel/get')
    .get()
    .json<HotelResponse>()
