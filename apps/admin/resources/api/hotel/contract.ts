import { AfterFetchContext, MaybeRef } from '@vueuse/core'

import { alternateDataAfterFetch, DateResponse, useAdminAPI } from '~api'

type SeasonID = number

export type ContractID = number

type SeasonResponse = {
  id: SeasonID
  contract_id: ContractID
  name: string
  date_start: DateResponse
  date_end: DateResponse
}

export type Season = {
  id: SeasonID
  contractID: ContractID
  name: string
  dateStart: DateResponse
  dateEnd: DateResponse
}

export interface ContractResponse {
  id: ContractID
  status: number
  date_start: DateResponse
  date_end: DateResponse
  seasons?: SeasonResponse[]
}

export type Contract = {
  id: ContractID
  status: number
  dateStart: DateResponse
  dateEnd: DateResponse
  seasons?: Season[]
}

const mapSeasonResponseToSeason = ({
  id,
  contract_id: contractID,
  name,
  date_start: dateStart,
  date_end: dateEnd,
}: SeasonResponse): Season => ({
  id,
  contractID,
  name,
  dateStart,
  dateEnd,
})

export const useHotelContractGetAPI = (props: MaybeRef<{ hotelID: number; contractID: number }>) =>
  useAdminAPI(props, ({ hotelID, contractID }) =>
    `/hotels/${hotelID}/contracts/${contractID}/get`, {
    afterFetch: (ctx: AfterFetchContext<ContractResponse>) =>
      alternateDataAfterFetch<ContractResponse, Contract>(
        ctx,
        ({
          id,
          status,
          date_start: dateStart,
          date_end: dateEnd,
          seasons,
        }) => ({
          id,
          status,
          dateStart,
          dateEnd,
          seasons: seasons?.map(mapSeasonResponseToSeason),
        }),
      ),
  })
    .get()
    .json<Contract>()
