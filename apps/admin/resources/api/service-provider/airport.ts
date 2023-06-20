import { computed } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { BaseResponse, useAdminAPI } from '~api'

import { getNullableRef } from '~lib/vue'

export interface GetAirportPricesPayload {
  providerId: number
  serviceId: number
}

export interface UpdateAirportPricePayload {
  providerId: number
  seasonId: number
  serviceId: number
  airportId: number
  currencyId: number
  priceNet?: number
  priceGross?: number
}

export interface ServicePriceResponse {
  id: number
  airport_id: number
  currency_id: number
  price_gross: number
  price_net: number
  season_id: number
  service_id: number
}

export const updateAirportPrice = (props: MaybeRef<UpdateAirportPricePayload>) =>
  useAdminAPI(
    props,
    ({ providerId, serviceId }) => `/service-provider/${providerId}/service-airport/${serviceId}/price`,
    { immediate: true },
  )
    .put(computed<string>(() => JSON.stringify(
      getNullableRef<UpdateAirportPricePayload, any>(
        props,
        (payload: UpdateAirportPricePayload): any => ({
          season_id: payload.seasonId,
          airport_id: payload.airportId,
          price_gross: payload.priceGross,
          price_net: payload.priceNet,
        }),
      ),
    )), 'application/json')
    .json<BaseResponse>()

export const useServiceProviderAirportPricesAPI = (props: MaybeRef<GetAirportPricesPayload>) =>
  useAdminAPI(props, ({ providerId, serviceId }) => `/service-provider/${providerId}/service-airport/${serviceId}/prices/get`)
    .get()
    .json<ServicePriceResponse[]>()
