import { computed } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { getNullableRef } from '~resources/vue/vue'

import { BaseResponse, useAdminAPI } from '~api'
import { UpdateBookingStatusResponse } from '~api/booking/hotel/index'

export type UpdateBookingPrice = {
  grossPrice?: number | null
  netPrice?: number | null
  grossPenalty?: number | null
  netPenalty?: number | null
}

export type UpdateBookingPricePayload = UpdateBookingPrice & {
  bookingID: number
}

export interface UpdateCarBookingPricePayload {
  carBookingId: number
  bookingID: number
  grossPrice?: number | null
}

export const updateBookingPrice = (props: MaybeRef<UpdateBookingPricePayload>) =>
  useAdminAPI(
    props,
    ({ bookingID }) => `/service-booking/${bookingID}/price`,
    { immediate: true },
  )
    .put(computed<string>(() => JSON.stringify(
      getNullableRef<UpdateBookingPricePayload, any>(
        props,
        (payload: UpdateBookingPricePayload): any => ({
          grossPrice: payload.grossPrice,
          netPrice: payload.netPrice,
          grossPenalty: payload.grossPenalty,
          netPenalty: payload.netPenalty,
        }),
      ),
    )), 'application/json')
    .json<UpdateBookingStatusResponse>()

export const updateCarBookingPrice = (props: MaybeRef<UpdateCarBookingPricePayload>) =>
  useAdminAPI(
    props,
    ({ bookingID, carBookingId }) => `/service-booking/${bookingID}/cars/${carBookingId}/price`,
    { immediate: true },
  )
    .put(computed<string>(() => JSON.stringify(
      getNullableRef<UpdateCarBookingPricePayload, any>(
        props,
        (payload: UpdateCarBookingPricePayload): any => ({
          clientPrice: payload.grossPrice,
        }),
      ),
    )), 'application/json')
    .json<BaseResponse>()

export const useRecalculateBookingPriceAPI = (props: MaybeRef<UpdateBookingPricePayload | null>) =>
  useAdminAPI(props, ({ bookingID }) =>
    `/service-booking/${bookingID}/price/recalculate`)
    .post()
    .json<BaseResponse>()
