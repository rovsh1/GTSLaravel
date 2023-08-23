import { computed } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { useAdminAPI } from '~api'
import { UpdateBookingStatusResponse } from '~api/booking/hotel/index'

import { getNullableRef } from '~lib/vue'

export interface UpdateBookingPricePayload {
  bookingID: number
  boPrice?: number | null
  hoPrice?: number | null
  boPenalty?: number | null
  hoPenalty?: number | null
}

export interface UpdateRoomBookingPricePayload {
  roomBookingId: number
  bookingID: number
  boPrice?: number | null
  hoPrice?: number | null
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
          boPrice: payload.boPrice,
          hoPrice: payload.hoPrice,
          boPenalty: payload.boPenalty,
          hoPenalty: payload.hoPenalty,
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
          boPrice: payload.boPrice,
          hoPrice: payload.hoPrice,
        }),
      ),
    )), 'application/json')
    .json<UpdateBookingStatusResponse>()