import { AfterFetchContext, MaybeRef } from '@vueuse/core'

import { alternateDataAfterFetch, useAdminAPI } from '~api'
import { HotelImage, HotelImageResponse, mapHotelImageResponseToImageResponse } from '~api/hotel/images'
import { HotelRoomID } from '~api/hotel/index'

import { HotelID } from './get'

type HotelRoomProps = {
  roomID: number
}

export type HotelRoomResponse = {
  id: HotelRoomID
  hotel_id: HotelID
  name: string
  rooms_number: number
  guests_count: number
  images?: HotelImageResponse[]
}

export type HotelRoom = {
  id: HotelRoomID
  hotelID: HotelID
  name: string
  roomsNumber: number
  guestsCount: number
  images?: HotelImage[]
}

export const mapHotelRoomResponseToHotelRoom = ({
  id,
  hotel_id: hotelID,
  name,
  rooms_number: roomsNumber,
  guests_count: guestsCount,
  images,
}: HotelRoomResponse): HotelRoom => ({
  id,
  hotelID,
  name,
  roomsNumber,
  guestsCount,
  images: images?.map(mapHotelImageResponseToImageResponse),
})

export const useHotelRoomAPI = (props: MaybeRef<HotelRoomProps | null>) =>
  useAdminAPI(props, ({ roomID }) =>
    `/rooms/${roomID}/get`, {
    afterFetch: (ctx: AfterFetchContext<HotelRoomResponse>) =>
      alternateDataAfterFetch<HotelRoomResponse, HotelRoom>(ctx, mapHotelRoomResponseToHotelRoom),
  })
    .get()
    .json<HotelRoom>()
