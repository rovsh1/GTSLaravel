import { MaybeRef } from '@vueuse/core'

import { DateResponse, useAdminAPI } from '~api'

type SeasonID = number

type ContractID = number

export type SeasonResponse = {
  id: SeasonID
  contract_id: ContractID
  name: string
  date_start: DateResponse
  date_end: DateResponse
}

export type ContractResponse = {
  id: ContractID
  date_start: DateResponse
  date_end: DateResponse
  seasons?: SeasonResponse[]
}

export const useHotelContractGetAPI = (props: MaybeRef<{ hotelID: number; contractID: number }>) =>
  useAdminAPI(props, ({ hotelID, contractID }) =>
    `/hotels/${hotelID}/contracts/${contractID}/get`)
    .get()
    .json<ContractResponse>()