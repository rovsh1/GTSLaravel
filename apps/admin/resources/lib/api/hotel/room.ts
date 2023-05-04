import { computed } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { useAdminAPI } from '~resources/lib/api'
import { HotelID } from '~resources/lib/api/hotel/hotel'
import { getRef } from '~resources/lib/vue'

export type HotelRoomID = number

export type HotelRoomResponse = {
  id: HotelRoomID
  hotel_id: HotelID
  name: string
  custom_name: string
  rooms_number: number
  guests_number: number
}

export const useHotelRoomAPI = (props: MaybeRef<{ hotelID: number; roomID?: number }>) =>
  useAdminAPI(computed(() => getRef(props, ({ hotelID, roomID }) =>
    `/hotels/${hotelID}/rooms/${roomID}/get`)), {
    beforeFetch(ctx) {
      if (getRef(props, ({ roomID }) => roomID) === undefined) ctx.cancel()
    },
  })
    .get()
    .json<HotelRoomResponse>()
