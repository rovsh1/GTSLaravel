import { MaybeRef } from '@vueuse/core'

import { useAdminAPI } from '~api'

export type AirportID = number

export type AirportResponse = {
  id: AirportID
  name: string
}

export const useAirportSearchAPI = (props: MaybeRef<{ cityID?: number }>) =>
  useAdminAPI(props, ({ cityID }) => (cityID ? `/airports/search?city_id=${cityID}` : '/airports/search'))
    .get()
    .json<AirportResponse[]>()
