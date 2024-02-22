import { useAdminAPI } from '~api'

export interface CountryResponse {
  id: number
  name: string
  code: string
}

export const useCountrySearchAPI = () =>
  useAdminAPI({ }, () => '/countries')
    .get()
    .json<CountryResponse[]>()
