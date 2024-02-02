import { MaybeRef } from '@vueuse/core'

import { useAdminAPI } from '~api'

export type RailwayStationID = number

export type RailwayStationResponse = {
  id: RailwayStationID
  name: string
}

export const useRailwayStationSearchAPI = (props: MaybeRef<{ cityID?: number }>) =>
  useAdminAPI(props, ({ cityID }) => (cityID ? `/railway-stations/search?city_id=${cityID}` : '/railway-stations/search'))
    .get()
    .json<RailwayStationResponse[]>()
