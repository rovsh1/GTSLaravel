import { computed } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { BaseResponse, useAdminAPI } from '~api'
import { Money } from '~api/models'

import { getNullableRef } from '~lib/vue'

export interface GetTransferPricesPayload {
  providerId: number
  serviceId: number
}

export interface UpdateCarPricePayload {
  providerId: number
  seasonId: number
  serviceId: number
  carId: number
  currencyId: number
  priceNet?: number
  pricesGross?: Money[]
}

export interface ServicePriceResponse {
  id: number
  car_id: number
  currency_id: number
  prices_gross: Money[]
  price_net: number
  season_id: number
  service_id: number
}

export const updateCarPrice = (props: MaybeRef<UpdateCarPricePayload>) =>
  useAdminAPI(
    props,
    ({ providerId, serviceId }) => `/service-provider/${providerId}/service-transfer/${serviceId}/price`,
    { immediate: true },
  )
    .put(computed<string>(() => JSON.stringify(
      getNullableRef<UpdateCarPricePayload, any>(
        props,
        (payload: UpdateCarPricePayload): any => ({
          season_id: payload.seasonId,
          car_id: payload.carId,
          prices_gross: payload.pricesGross,
          price_net: payload.priceNet,
          currency_id: payload.currencyId,
        }),
      ),
    )), 'application/json')
    .json<BaseResponse>()

export const useServiceProviderTransferPricesAPI = (props: MaybeRef<GetTransferPricesPayload>) =>
  useAdminAPI(props, ({ providerId, serviceId }) => `/service-provider/${providerId}/service-transfer/${serviceId}/prices/get`)
    .get()
    .json<ServicePriceResponse[]>()
