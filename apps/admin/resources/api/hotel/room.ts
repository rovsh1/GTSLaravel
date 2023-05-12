import { AfterFetchContext, MaybeRef } from '@vueuse/core'

import { alternateDataAfterFetch, useAdminAPI } from '~api'
import { HotelImage, HotelImageResponse, mapHotelImageResponseToImageResponse } from '~api/hotel/images'
import { HotelRoomID } from '~api/hotel/index'

import { HotelID } from './get'

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
  images: HotelImageResponse[]
}

export type HotelRoom = {
  id: HotelRoomID
  hotelID: HotelID
  name: string
  customName: string
  roomsNumber: number
  guestsNumber: number
  images: HotelImage[]
}

export const mapHotelRoomResponseToHotelRoom = ({
  id,
  hotel_id: hotelID,
  name,
  custom_name: customName,
  rooms_number: roomsNumber,
  guests_number: guestsNumber,
  images,
}: HotelRoomResponse): HotelRoom => ({
  id,
  hotelID,
  name,
  customName,
  roomsNumber,
  guestsNumber,
  images: images.map(mapHotelImageResponseToImageResponse),
})

export const useHotelRoomAPI = (props: MaybeRef<HotelRoomProps | null>) =>
  useAdminAPI(props, ({ hotelID, roomID }) =>
    `/hotels/${hotelID}/rooms/${roomID}/get`, {
    afterFetch: (ctx: AfterFetchContext<HotelRoomResponse>) =>
      alternateDataAfterFetch<HotelRoomResponse, HotelRoom>(ctx, mapHotelRoomResponseToHotelRoom),
  })
    .get()
    .json<HotelRoom>()
