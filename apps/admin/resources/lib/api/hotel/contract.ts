import { computed } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { DateResponse, useAdminAPI } from '~resources/lib/api'
import { getRef } from '~resources/lib/vue'

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
  useAdminAPI(props)(computed(() => getRef(props, ({ hotelID, contractID }) =>
    `/hotels/${hotelID}/contracts/${contractID}/get`)))
    .get()
    .json<ContractResponse>()
