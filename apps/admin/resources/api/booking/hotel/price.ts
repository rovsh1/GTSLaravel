import { computed } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { useAdminAPI } from '~api'
import { UpdateBookingStatusResponse } from '~api/booking/hotel/index'

import { getNullableRef } from '~lib/vue'

export interface UpdateBookingPricePayload {
  bookingID: number
  grossPrice?: number | null
  netPrice?: number | null
  grossPenalty?: number | null
  netPenalty?: number | null
}

export interface UpdateRoomBookingPricePayload {
  roomBookingId: number
  bookingID: number
  grossPrice?: number | null
  netPrice?: number | null
}

export const updateBookingPrice = (props: MaybeRef<UpdateBookingPricePayload>) =>
  useAdminAPI(
    props,
    ({ bookingID }) => `/hotel-booking/${bookingID}/price`,
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

export const updateRoomBookingPrice = (props: MaybeRef<UpdateRoomBookingPricePayload>) =>
  useAdminAPI(
    props,
    ({ bookingID, roomBookingId }) => `/hotel-booking/${bookingID}/rooms/${roomBookingId}/price`,
    { immediate: true },
  )
    .put(computed<string>(() => JSON.stringify(
      getNullableRef<UpdateRoomBookingPricePayload, any>(
        props,
        (payload: UpdateRoomBookingPricePayload): any => ({
          grossPrice: payload.grossPrice,
          netPrice: payload.netPrice,
        }),
      ),
    )), 'application/json')
    .json<UpdateBookingStatusResponse>()
