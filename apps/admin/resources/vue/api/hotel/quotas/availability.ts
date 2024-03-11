import { computed } from 'vue'

import { AfterFetchContext, MaybeRef } from '@vueuse/core'

import { getNullableRef } from '~resources/vue/vue'

import { alternateDataAfterFetch, useAdminAPI } from '~api'

type QuotaAvailabilityFiltersPayload = {
  dateFrom: string
  dateTo: string
  cityIds: number[]
  hotelIds: number[]
  roomIds: number[]
  roomTypeIds: number[]
}

export type HotelInfo = {
  id: number
  name: string
}

export type QuotaInfoResponse = {
  date: string
  count_available: number | null
}

export type QuotaInfo = {
  date: string
  countAvailable: number | null
}

export type QuotaAvailabilityResponse = {
  hotel: HotelInfo
  quotas: QuotaInfoResponse[]
}

export type QuotaAvailability = {
  hotel: HotelInfo
  quotas: QuotaInfo[]
}

export const useQuotaAvailability = (props: MaybeRef<QuotaAvailabilityFiltersPayload | null>) =>
  useAdminAPI(
    props,
    () =>
      '/hotels/quotas/availability',
    {
      afterFetch: (ctx: AfterFetchContext<QuotaAvailabilityResponse[]>) =>
        alternateDataAfterFetch<QuotaAvailabilityResponse[], QuotaAvailability[]>(ctx, (data) =>
          (data.length > 0 ? data.map(({
            hotel,
            quotas,
          }: QuotaAvailabilityResponse): QuotaAvailability => ({
            hotel,
            quotas: quotas?.map(({
              date,
              count_available: countAvailable,
            }: QuotaInfoResponse): QuotaInfo => ({
              date,
              countAvailable,
            })),
          })) : [])),
    },
  )
    .post(computed<string>(() => JSON.stringify(
      getNullableRef<QuotaAvailabilityFiltersPayload, any>(
        props,
        (payload: QuotaAvailabilityFiltersPayload): any => ({
          ...payload,
        }),
      ),
    )), 'application/json')
    .json<QuotaAvailability[]>()
