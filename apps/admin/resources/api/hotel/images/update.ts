import { MaybeRef } from '@vueuse/core'

import { BaseResponse, useAdminAPI } from '~api'
import { HotelRoomID } from '~api/hotel'
import { HotelID } from '~api/hotel/get'

import { HotelImageID } from '.'

type HotelRoomImageUpdateProps = {
  hotelID: HotelID
  roomID: HotelRoomID
  imageID: HotelImageID
}

export const useHotelRoomAttachImageAPI = (props: MaybeRef<HotelRoomImageUpdateProps | null>) =>
  useAdminAPI(props, ({ hotelID, roomID, imageID }) =>
    `/hotels/${hotelID}/rooms/${roomID}/images/${imageID}/set`)
    .post()
    .json<BaseResponse>()

export const useHotelRoomDetachImageAPI = (props: MaybeRef<HotelRoomImageUpdateProps | null>) =>
  useAdminAPI(props, ({ hotelID, roomID, imageID }) =>
    `/hotels/${hotelID}/rooms/${roomID}/images/${imageID}/unset`)
    .post()
    .json<BaseResponse>()
