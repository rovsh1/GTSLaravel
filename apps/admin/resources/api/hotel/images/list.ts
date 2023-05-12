import { AfterFetchContext, MaybeRef } from '@vueuse/core'

import { alternateDataAfterFetch, useAdminAPI } from '~api'
import { HotelRoomID } from '~api/hotel'

import { HotelImage, HotelImageID, HotelImageResponse, mapHotelImageResponseToImageResponse } from '.'

type HotelImagesResponse = HotelImageResponse[]

export type UseHotelImages = HotelImage[] | null

export const useHotelImagesListAPI = (props: MaybeRef<{ hotelID: number }>) =>
  useAdminAPI(props, ({ hotelID }) =>
    `/hotels/${hotelID}/images/get`, {
    afterFetch: (ctx: AfterFetchContext<HotelImagesResponse>) =>
      alternateDataAfterFetch<HotelImagesResponse, UseHotelImages>(ctx, (data) =>
        (data.length > 0 ? data.map(mapHotelImageResponseToImageResponse) : null)),
  })
    .get()
    .json<UseHotelImages>()

type HotelRoomImagesProps = {
  hotelID: number
  roomID: number
}

export type RoomImageResponse = Omit<HotelImageResponse, 'id'> & {
  image_id: HotelImageID
  room_id: HotelRoomID
}

type RoomImagesResponse = RoomImageResponse[]

export type HotelRoomImage = HotelImage & {
  roomID: HotelRoomID
}

export type UseHotelRoomImages = HotelRoomImage[] | null

export const useHotelRoomImagesAPI = (props: MaybeRef<HotelRoomImagesProps | null>) =>
  useAdminAPI(props, ({ hotelID, roomID }) =>
    `/hotels/${hotelID}/images/${roomID}/list`, {
    afterFetch: (ctx: AfterFetchContext<RoomImagesResponse>) =>
      alternateDataAfterFetch<RoomImagesResponse, UseHotelRoomImages>(ctx, (data) =>
        (data.length > 0 ? data.map(({
          image_id: id,
          hotel_id: hotelID,
          room_id: roomID,
          title,
          order,
          file,
        }) => ({
          id,
          hotelID,
          roomID,
          title,
          order,
          file,
        })) : null)),
  })
    .get()
    .json<UseHotelRoomImages>()
