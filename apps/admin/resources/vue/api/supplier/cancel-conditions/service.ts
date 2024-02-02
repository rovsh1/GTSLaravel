import { computed } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { getNullableRef } from '~resources/vue/vue'

import { useAdminAPI } from '~api'
import { Percent } from '~api/hotel/markup-settings'

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
}

interface GetCancelConditionsPayload {
  supplierId: number
  serviceId: number
  seasonId: number
}

export interface UpdateCancelConditionsPayload extends GetCancelConditionsPayload {
  cancelConditions: ServiceCancelConditions
}

export const getCancelConditions = (payload: MaybeRef<GetCancelConditionsPayload>) =>
  useAdminAPI(
    payload,
    ({ supplierId, serviceId, seasonId }) => `/supplier/${supplierId}/service-airport/${serviceId}/cancel-conditions?season_id=${seasonId}`,
    { immediate: true },
  )
    .get()
    .json<ServiceCancelConditions>()

export const getExistsCancelConditions = (payload: MaybeRef<{ supplierId: number }>) =>
  useAdminAPI(
    payload,
    ({ supplierId }) => `/supplier/${supplierId}/service-airport/cancel-conditions/get`,
  )
    .get()
    .json<ExistCancelConditions[]>()

export const updateCancelConditions = (payload: MaybeRef<UpdateCancelConditionsPayload>) =>
  useAdminAPI(
    payload,
    ({ supplierId, serviceId }) => `/supplier/${supplierId}/service-airport/${serviceId}/cancel-conditions`,
    { immediate: true },
  )
    .put(computed<string>(() => JSON.stringify(
      getNullableRef<UpdateCancelConditionsPayload, any>(
        payload,
        (request: UpdateCancelConditionsPayload): any => ({
          cancel_conditions: request.cancelConditions,
          season_id: request.seasonId,
        }),
      ),
    )), 'application/json')
    .json<ServiceCancelConditions>()
