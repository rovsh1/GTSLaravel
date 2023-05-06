import { MaybeRef } from '@vueuse/core'

import { useAdminAPI } from '~resources/lib/api'
import { HotelID } from '~resources/lib/api/hotel/hotel'

export type HotelRoomID = number

type HotelRoomProps = {
  hotelID: number
  roomID: number
}

export type HotelRoomResponse = {
  id: HotelRoomID
  hotel_id: HotelID
  name: string
  custom_name: string
  rooms_number: number
  guests_number: number
}

export const useHotelRoomAPI = (props: MaybeRef<HotelRoomProps | null>) =>
  useAdminAPI(props, ({ hotelID, roomID }) =>
    `/hotels/${hotelID}/rooms/${roomID}/get`)
    .get()
    .json<HotelRoomResponse>()
