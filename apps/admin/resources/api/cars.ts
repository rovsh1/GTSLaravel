import { useAdminAPI } from '~api/index'

export interface CarResponse {
  id: number
  name: string
  type: number
}

export const useCountrySearchAPI = () =>
  useAdminAPI({ }, () => '/countries/search')
    .get()
    .json<CarResponse[]>()
