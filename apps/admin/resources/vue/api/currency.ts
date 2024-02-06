import { useAdminAPI } from '~api'
import { Currency } from '~api/models'

export const useCurrencyGetAPI = () =>
  useAdminAPI({}, () => '/currencies/get')
    .get()
    .json<Currency[]>()
