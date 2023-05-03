import { computed } from 'vue'

import { AfterFetchContext, MaybeRef } from '@vueuse/core'

import { alternateDataAfterFetch, useAdminAPI } from '~resources/lib/api'
import { HotelRoomResponse } from '~resources/lib/api/hotel/room'
import { getRef } from '~resources/lib/vue'

type HotelRoomsResponse = HotelRoomResponse[]

export type HotelRoom = Omit<HotelRoomResponse, 'id'> & {
  id: number
}

export type UseHotelRooms = HotelRoom[] | null

export const useHotelRoomsListAPI = (props: MaybeRef<{ hotelID: number }>) =>
  useAdminAPI(computed(() => getRef(props, ({ hotelID }) =>
    `/hotels/${hotelID}/rooms/list`)), {
    afterFetch: (ctx: AfterFetchContext<HotelRoomsResponse>) =>
      alternateDataAfterFetch<HotelRoomsResponse, UseHotelRooms>(ctx, (data) =>
        (data.length > 0 ? data.map(({ id, ...rest }) => ({
          id: Number(id),
          ...rest,
        })) : null)),
  })
    .get()
    .json<UseHotelRooms>()
