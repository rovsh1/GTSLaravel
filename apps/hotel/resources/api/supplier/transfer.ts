import { computed } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { BaseResponse, useAdminAPI } from '~api'
import { Money } from '~api/models'

import { getNullableRef } from '~lib/vue'

export interface GetTransferPricesPayload {
  supplierId: number
  serviceId: number
}

export interface UpdateCarPricePayload {
  supplierId: number
  seasonId: number
  serviceId: number
  carId: number
  currency: string
  priceNet?: number
  pricesGross?: Money[]
}

export interface ServicePriceResponse {
  id: number
  car_id: number
  currency: string
  prices_gross: Money[]
  price_net: number
  season_id: number
  service_id: number
}

export const updateCarPrice = (props: MaybeRef<UpdateCarPricePayload>) =>
  useAdminAPI(
    props,
    ({ supplierId, serviceId }) => `/supplier/${supplierId}/service-transfer/${serviceId}/price`,
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
          currency: payload.currency,
        }),
      ),
    )), 'application/json')
    .json<BaseResponse>()

export const useServiceProviderTransferPricesAPI = (props: MaybeRef<GetTransferPricesPayload>) =>
  useAdminAPI(props, ({ supplierId, serviceId }) => `/supplier/${supplierId}/service-transfer/${serviceId}/prices/get`)
    .get()
    .json<ServicePriceResponse[]>()
