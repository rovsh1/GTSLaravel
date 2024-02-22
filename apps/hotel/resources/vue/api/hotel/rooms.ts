import { AfterFetchContext } from '@vueuse/core'

import { alternateDataAfterFetch, useAdminAPI } from '~api'
import { HotelRoom, HotelRoomResponse, mapHotelRoomResponseToHotelRoom } from '~api/hotel/room'

type HotelRoomsResponse = HotelRoomResponse[]

export type UseHotelRooms = HotelRoom[] | null

export const useHotelRoomsListAPI = () =>
  useAdminAPI({}, () =>
    '/rooms/list', {
    afterFetch: (ctx: AfterFetchContext<HotelRoomsResponse>) =>
      alternateDataAfterFetch<HotelRoomsResponse, UseHotelRooms>(ctx, (data) =>
        (data.length > 0 ? data.map(mapHotelRoomResponseToHotelRoom) : null)),
  })
    .get()
    .json<UseHotelRooms>()
