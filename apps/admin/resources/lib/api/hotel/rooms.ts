import { computed } from 'vue'

import { AfterFetchContext, MaybeRef } from '@vueuse/core'

import { alternateDataAfterFetch, useAdminAPI } from '~resources/lib/api'
import { HotelRoomResponse } from '~resources/lib/api/hotel/room'
import { getNullableRef } from '~resources/lib/vue'

type HotelRoomsResponse = HotelRoomResponse[]

export type HotelRoom = Omit<HotelRoomResponse, 'id'> & {
  id: number
}

type HotelRoomsListProps = {
  hotelID: number
}

export type UseHotelRooms = HotelRoom[] | null

export const useHotelRoomsListAPI = (props: MaybeRef<HotelRoomsListProps | null>) => {
  const url = computed(() =>
    getNullableRef(props, ({ hotelID }) =>
      `/hotels/${hotelID}/rooms/list`, ''))
  return useAdminAPI(url, {
    afterFetch: (ctx: AfterFetchContext<HotelRoomsResponse>) =>
      alternateDataAfterFetch<HotelRoomsResponse, UseHotelRooms>(ctx, (data) =>
        (data.length > 0 ? data.map(({
          id,
          ...rest
        }) => ({
          id: Number(id),
          ...rest,
        })) : null)),
  })
    .get()
    .json<UseHotelRooms>()
}
