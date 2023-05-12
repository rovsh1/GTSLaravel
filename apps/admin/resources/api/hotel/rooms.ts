import { AfterFetchContext, MaybeRef } from '@vueuse/core'

import { alternateDataAfterFetch, useAdminAPI } from '~api'
import { HotelRoom, HotelRoomResponse, mapHotelRoomResponseToHotelRoom } from '~api/hotel/room'

type HotelRoomsResponse = HotelRoomResponse[]

type HotelRoomsListProps = {
  hotelID: number
}

export type UseHotelRooms = HotelRoom[] | null

export const useHotelRoomsListAPI = (props: MaybeRef<HotelRoomsListProps | null>) =>
  useAdminAPI(props, ({ hotelID }) =>
    `/hotels/${hotelID}/rooms/list`, {
    afterFetch: (ctx: AfterFetchContext<HotelRoomsResponse>) =>
      alternateDataAfterFetch<HotelRoomsResponse, UseHotelRooms>(ctx, (data) =>
        (data.length > 0 ? data.map(mapHotelRoomResponseToHotelRoom) : null)),
  })
    .get()
    .json<UseHotelRooms>()
