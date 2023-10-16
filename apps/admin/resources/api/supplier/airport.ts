import { computed } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { BaseResponse, useAdminAPI } from '~api'
import { Money } from '~api/models'

import { getNullableRef } from '~lib/vue'

export interface GetAirportPricesPayload {
  supplierId: number
  serviceId: number
}

export interface UpdateAirportPricePayload {
  supplierId: number
  seasonId: number
  serviceId: number
  currency: string
  priceNet?: number
  pricesGross?: Money[]
}

export interface ServicePriceResponse {
  id: number
  currency: string
  prices_gross: Money[]
  price_net: number
  season_id: number
  service_id: number
}

export const updateAirportPrice = (props: MaybeRef<UpdateAirportPricePayload>) =>
  useAdminAPI(
    props,
    ({ supplierId, serviceId }) => `/supplier/${supplierId}/service-airport/${serviceId}/price`,
    { immediate: true },
  )
    .put(computed<string>(() => JSON.stringify(
      getNullableRef<UpdateAirportPricePayload, any>(
        props,
        (payload: UpdateAirportPricePayload): any => ({
          season_id: payload.seasonId,
          prices_gross: payload.pricesGross,
          price_net: payload.priceNet,
          currency: payload.currency,
        }),
      ),
    )), 'application/json')
    .json<BaseResponse>()

export const useServiceProviderAirportPricesAPI = (props: MaybeRef<GetAirportPricesPayload>) =>
  useAdminAPI(props, ({ supplierId, serviceId }) => `/supplier/${supplierId}/service-airport/${serviceId}/prices/get`)
    .get()
    .json<ServicePriceResponse[]>()
