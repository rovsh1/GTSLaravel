import { MaybeRef } from '@vueuse/core'

import { useAdminAPI } from '~api'

export type CityID = number

export type AirportID = number

export type AirportResponse = {
  id: AirportID
  name: string
  country_id: CityID
  country_name?: string
  center_lat: number
  center_lon: number
}

export const useCitySearchAPI = (props: MaybeRef<{ cityID?: number }>) =>
  useAdminAPI(props, () => '/cities/search')
    .get()
    .json<AirportResponse[]>()
