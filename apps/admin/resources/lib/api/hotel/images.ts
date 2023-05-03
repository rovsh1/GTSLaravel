import { computed, unref } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { useAdminAPI } from '~resources/lib/api'
import { getRef } from '~resources/lib/vue'

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
  useAdminAPI(computed(() => getRef(props, ({ hotelID }) =>
    `/hotels/${hotelID}/images/get`)))
    .get()
    .json<HotelImageResponse[]>()

export const useHotelRoomImagesAPI = (props: MaybeRef<{ hotelID: number; roomID?: number }>) =>
  useAdminAPI(computed(() =>
    getRef(props, ({ hotelID, roomID }) =>
      `/hotels/${hotelID}/images/${roomID}/list`)), {
    beforeFetch(ctx) {
      if (getRef(props, ({ roomID }) => roomID) === undefined) ctx.cancel()
    },
  })
    .get()
    .json<RoomImageResponse[]>()

type HotelImagesUploadProps = {
  hotelID: number
  roomID?: number
  images: File[] | null
}
export const useHotelImagesUploadAPI = (props: MaybeRef<HotelImagesUploadProps>) =>
  useAdminAPI(computed(() => getRef(props, ({ hotelID }) =>
    `/hotels/${hotelID}/images/upload`)), {
    beforeFetch(ctx) {
      if (getRef(props, ({ images }) => images) === null) ctx.cancel()
    },
  })
    .post(computed<FormData>(() => {
      const { roomID, images } = unref(props)
      const formData = new FormData()
      images?.forEach((image) => {
        formData.append('files[]', image)
      })
      if (roomID !== undefined) {
        formData.append('room_id', String(roomID))
      }
      return formData
    }))

export const useHotelImageRemoveAPI = (props: MaybeRef<{ hotelID: number; imageID: number | null }>) =>
  useAdminAPI(computed(() => getRef(props, ({ hotelID, imageID }) =>
    `/hotels/${hotelID}/images/${imageID}`)), {
    beforeFetch(ctx) {
      if (getRef(props, ({ imageID }) => imageID) === null) ctx.cancel()
    },
  }).delete()
    .json<{ success: boolean }>()

type HotelImagesReorderProps = {
  hotelID: number
  imagesIDs: HotelImageResponse['id'][] | null
}
export const useHotelImagesReorderAPI = (props: MaybeRef<HotelImagesReorderProps>) =>
  useAdminAPI(computed(() => getRef(props, ({ hotelID }) =>
    `/hotels/${hotelID}/images/reorder`)), {
    beforeFetch(ctx) {
      if (unref(props).imagesIDs === null) ctx.cancel()
    },
  })
    .post(computed<{ indexes: number[] }>(() =>
      getRef(props, ({ imagesIDs }) =>
        ({ indexes: imagesIDs ?? [] }))))
    .json<{ success: boolean }>()

type HotelRoomImageProps = {
  hotelID: number
  roomID: number
  imageID: number
}

export const useHotelRoomImageCreateAPI = (props: MaybeRef<HotelRoomImageProps>) =>
  useAdminAPI(computed(() => getRef(props, ({ hotelID, roomID, imageID }) =>
    `/hotels/${hotelID}/rooms/${roomID}/images/${imageID}/set`)))
    .post()

export const useHotelRoomImageDeleteAPI = (props: MaybeRef<HotelRoomImageProps>) =>
  useAdminAPI(getRef(props, ({ hotelID, roomID, imageID }) =>
    `/hotels/${hotelID}/rooms/${roomID}/images/${imageID}/unset`))
    .post()
