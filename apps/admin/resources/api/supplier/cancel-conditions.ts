import { computed } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { useAdminAPI } from '~api'
import { Percent } from '~api/hotel/markup-settings'

import { getNullableRef } from '~lib/vue'

type MaybeEmptyString<T> = T | ''

export type DailyMarkup = {
  percent: MaybeEmptyString<Percent>
  daysCount: MaybeEmptyString<number>
}

export type ServiceCancelConditions = {
  noCheckInMarkup: { percent: MaybeEmptyString<Percent> }
  dailyMarkups: DailyMarkup[]
}

export type ExistCancelConditions = {
  service_id: number
  season_id: number
  car_id: number
}

interface GetCancelConditionsPayload {
  supplierId: number
  serviceId: number
  seasonId: number
  carId: number
}

export interface UpdateCancelConditionsPayload extends GetCancelConditionsPayload {
  cancelConditions: ServiceCancelConditions
}

export const getCancelConditions = (payload: MaybeRef<GetCancelConditionsPayload>) =>
  useAdminAPI(
    payload,
    ({ supplierId, serviceId, seasonId, carId }) => `/supplier/${supplierId}/service-transfer/${serviceId}/cancel-conditions?season_id=${seasonId}&car_id=${carId}`,
    { immediate: true },
  )
    .get()
    .json<ServiceCancelConditions>()

export const getExistsCancelConditions = (payload: MaybeRef<{ supplierId: number }>) =>
  useAdminAPI(
    payload,
    ({ supplierId }) => `/supplier/${supplierId}/service-transfer/cancel-conditions/get`,
  )
    .get()
    .json<ExistCancelConditions[]>()

export const updateCancelConditions = (payload: MaybeRef<UpdateCancelConditionsPayload>) =>
  useAdminAPI(
    payload,
    ({ supplierId, serviceId }) => `/supplier/${supplierId}/service-transfer/${serviceId}/cancel-conditions`,
    { immediate: true },
  )
    .put(computed<string>(() => JSON.stringify(
      getNullableRef<UpdateCancelConditionsPayload, any>(
        payload,
        (request: UpdateCancelConditionsPayload): any => ({
          cancel_conditions: request.cancelConditions,
          season_id: request.seasonId,
          car_id: request.carId,
        }),
      ),
    )), 'application/json')
    .json<ServiceCancelConditions>()
