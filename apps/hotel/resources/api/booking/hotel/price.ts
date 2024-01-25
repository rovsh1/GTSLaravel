import { computed } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { useAdminAPI } from '~api'
import { UpdateBookingStatusResponse } from '~api/booking/hotel/index'

import { getNullableRef } from '~lib/vue'

export type UpdateBookingPrice = {
  grossPrice?: number | null
  netPrice?: number | null
  grossPenalty?: number | null
  netPenalty?: number | null
}

export type UpdateBookingPricePayload = UpdateBookingPrice & {
  bookingID: number
}

export const updateBookingPrice = (props: MaybeRef<UpdateBookingPricePayload>) =>
  useAdminAPI(
    props,
    ({ bookingID }) => `/booking/${bookingID}/price`,
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
