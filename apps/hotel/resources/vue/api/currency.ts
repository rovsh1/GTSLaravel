import { useAdminAPI } from '~api'
import { Currency } from '~api/models'

export const useCurrencyGetAPI = () =>
  useAdminAPI({}, () => '/currencies')
    .get()
    .json<Currency[]>()
