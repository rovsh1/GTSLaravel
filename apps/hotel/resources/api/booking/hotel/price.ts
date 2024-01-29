import { computed } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { BaseResponse, useAdminAPI } from '~api'

import { getNullableRef } from '~lib/vue'

export type UpdateBookingPrice = {
  penalty: number | null
}

export type UpdateBookingPricePayload = UpdateBookingPrice & {
  bookingID: number
}

export const updateBookingPrice = (props: MaybeRef<UpdateBookingPricePayload>) =>
  useAdminAPI(
    props,
    ({ bookingID }) => `/booking/${bookingID}/price/penalty`,
    { immediate: true },
  )
    .put(computed<string>(() => JSON.stringify(
      getNullableRef<UpdateBookingPricePayload, any>(
        props,
        (payload: UpdateBookingPricePayload): any => ({
          penalty: payload.penalty,
        }),
      ),
    )), 'application/json')
    .json<BaseResponse>()