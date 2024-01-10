import { useAdminAPI } from '~api/index'

export interface CountryResponse {
  id: number
  name: string
  code: string
}

export const useCountrySearchAPI = () =>
  useAdminAPI({ }, () => '/countries')
    .get()
    .json<CountryResponse[]>()
