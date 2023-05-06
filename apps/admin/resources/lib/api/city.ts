import { MaybeRef } from '@vueuse/core'

import { useAdminAPI } from '~lib/api/index'

export type CountryID = number

export type CityID = number

export type CityResponse = {
  id: CityID
  name: string
  country_id: CountryID
  country_name?: string
  center_lat: number
  center_lon: number
}

export const useCitySearchAPI = (props: MaybeRef<{ countryID: number }>) =>
  useAdminAPI(props, () => '/cities/search')
    .get()
    .json<CityResponse[]>()
