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

type HotelImageUpdateProps = Omit<HotelRoomImageUpdateProps, 'roomID'>

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

export const useHotelSetMainImageAPI = (props: MaybeRef<HotelImageUpdateProps | null>) =>
  useAdminAPI(props, ({ hotelID, imageID }) =>
    `/hotels/${hotelID}/images/${imageID}/main/set`)
    .post()
    .json<BaseResponse>()

export const useHotelUnSetMainImageAPI = (props: MaybeRef<HotelImageUpdateProps | null>) =>
  useAdminAPI(props, ({ hotelID, imageID }) =>
    `/hotels/${hotelID}/images/${imageID}/main/unset`)
    .post()
    .json<BaseResponse>()
