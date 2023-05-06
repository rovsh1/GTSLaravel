import { computed, unref } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { useAdminAPI } from '~resources/lib/api'
import { getNullableRef } from '~resources/lib/vue'

export type FileResponse = {
  guid: string
  name: string
  url: string
  entity_id: number
}

export type HotelImageResponse = {
  id: number
  title: string | null
  order: number
  hotel_id: number
  file: FileResponse
}

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

type HotelImagesUploadProps = {
  hotelID: number
  roomID?: number
  images: File[]
}
export const useHotelImagesUploadAPI = (props: MaybeRef<HotelImagesUploadProps | null>) =>
  useAdminAPI(props, ({ hotelID }) =>
    `/hotels/${hotelID}/images/upload`)
    .post(computed<FormData | null>(() => {
      const unwrapped = unref(props)
      if (unwrapped === null) return null
      const { roomID, images } = unwrapped
      const formData = new FormData()
      images.forEach((image) => {
        formData.append('files[]', image)
      })
      if (roomID !== undefined) {
        formData.append('room_id', String(roomID))
      }
      return formData
    }))
    .json<{ success: boolean }>()

type HotelImageRemoveProps = {
  hotelID: number
  imageID: number
}

export const useHotelImageRemoveAPI = (props: MaybeRef<HotelImageRemoveProps | null>) =>
  useAdminAPI(props, ({ hotelID, imageID }) =>
    `/hotels/${hotelID}/images/${imageID}`)
    .delete()
    .json<{ success: boolean }>()

type HotelImagesReorderProps = {
  hotelID: number
  imagesIDs: HotelImageResponse['id'][]
}

type HotelImagesReorderPayload = {
  indexes: number[]
}

export const useHotelImagesReorderAPI = (props: MaybeRef<HotelImagesReorderProps | null>) =>
  useAdminAPI(props, ({ hotelID }) =>
    `/hotels/${hotelID}/images/reorder`)
    .post(computed<string>(() => JSON.stringify(
      getNullableRef<HotelImagesReorderProps, HotelImagesReorderPayload>(
        props,
        ({ imagesIDs }) => ({ indexes: imagesIDs }),
      ),
    )), 'application/json')
    .json<{ success: boolean }>()

type HotelRoomImageProps = {
  hotelID: number
  roomID: number
  imageID: number
}

export const useHotelRoomImageCreateAPI = (props: MaybeRef<HotelRoomImageProps>) =>
  useAdminAPI(props, ({ hotelID, roomID, imageID }) =>
    `/hotels/${hotelID}/rooms/${roomID}/images/${imageID}/set`)
    .post()

export const useHotelRoomImageDeleteAPI = (props: MaybeRef<HotelRoomImageProps>) =>
  useAdminAPI(props, ({ hotelID, roomID, imageID }) =>
    `/hotels/${hotelID}/rooms/${roomID}/images/${imageID}/unset`)
    .post()
