import { computed } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { useAdminAPI } from '~api'
import { UpdateBookingStatusResponse } from '~api/booking/index'

import { getNullableRef } from '~lib/vue'

export interface UpdateBookingPricePayload {
  bookingID: number
  boPrice?: number
  hoPrice?: number
}

export interface UpdateRoomBookingPricePayload extends UpdateBookingPricePayload {
  roomBookingId: number
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
