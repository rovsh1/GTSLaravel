import { MaybeRef } from '@vueuse/core'

import { useAdminAPI } from '~lib/api'

import { HotelImageResponse } from '.'

export type RoomImageResponse = HotelImageResponse & {
  room_id: number
  image_id: number
}

export const useHotelImagesListAPI = (props: MaybeRef<{ hotelID: number }>) =>
  useAdminAPI(props, ({ hotelID }) =>
    `/hotels/${hotelID}/images/get`)
    .get()
    .json<HotelImageResponse[]>()

type HotelRoomImagesProps = {
  hotelID: number
  roomID: number
}

export const useHotelRoomImagesAPI = (props: MaybeRef<HotelRoomImagesProps | null>) =>
  useAdminAPI(props, ({ hotelID, roomID }) =>
    `/hotels/${hotelID}/images/${roomID}/list`)
    .get()
    .json<RoomImageResponse[]>()
