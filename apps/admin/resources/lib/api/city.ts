import { computed, unref } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { getURL, useAdminAPI } from '~resources/lib/api/index'

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
  useAdminAPI(props)(computed(() => getURL('/cities/search', unref(props))))
    .get()
    .json<CityResponse[]>()
