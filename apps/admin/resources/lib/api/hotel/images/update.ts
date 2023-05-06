import { MaybeRef } from '@vueuse/core'

import { useAdminAPI } from '~lib/api'
import { HotelID } from '~lib/api/hotel/get'
import { HotelRoomID } from '~lib/api/hotel/room'

import { HotelImageID } from '.'

type HotelRoomImageUpdateProps = {
  hotelID: HotelID
  roomID: HotelRoomID
  imageID: HotelImageID
}

export const useHotelRoomImageCreateAPI = (props: MaybeRef<HotelRoomImageUpdateProps>) =>
  useAdminAPI(props, ({ hotelID, roomID, imageID }) =>
    `/hotels/${hotelID}/rooms/${roomID}/images/${imageID}/set`)
    .post()

export const useHotelRoomImageDeleteAPI = (props: MaybeRef<HotelRoomImageUpdateProps>) =>
  useAdminAPI(props, ({ hotelID, roomID, imageID }) =>
    `/hotels/${hotelID}/rooms/${roomID}/images/${imageID}/unset`)
    .post()
