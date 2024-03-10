import { MaybeRef } from '@vueuse/core'

import { DateResponse, getURL, useAdminAPI } from '~api'
import { CityID } from '~api/city'

export type HotelID = number

export type HotelResponse = {
  id: HotelID
  name: string
  city_id: CityID
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

export const useHotelGetAPI = (props: MaybeRef<{ hotelID: number }>) =>
  useAdminAPI(props, ({ hotelID }) =>
    `/hotels/${hotelID}/get`)
    .get()
    .json<HotelResponse>()

export const useHotelSearchAPI = (props: MaybeRef<{ cityId?: number; cityIds?: number[] }>) =>
  useAdminAPI(props, ({ cityId, cityIds }) => {
    let queryParams: any
    if (cityId) {
      queryParams = { city_id: cityId }
    } if (cityIds) {
      queryParams = { city_ids: cityIds }
    }
    return getURL('/hotels/search', queryParams)
  })
    .get()
    .json<HotelResponse[]>()
