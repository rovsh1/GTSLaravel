import { computed, unref } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { Contract, Hotel, Room } from '~resources/lib/models'
import { HotelImage, RoomImage } from '~resources/views/hotel/images/models'

import { useAdminAPI } from '.'

export const useHotelAPI = (props: MaybeRef<{ hotelID: number }>) => {
  const { hotelID } = unref(props)
  return useAdminAPI(`/hotels/${hotelID}/get`)
    .get()
    .json<Hotel>()
}

export const useHotelImagesListAPI = (props: MaybeRef<{ hotelID: number }>) => {
  const { hotelID } = unref(props)
  return useAdminAPI(`/hotels/${hotelID}/images/get`)
    .get()
    .json<HotelImage[]>()
}

export const useHotelRoomImagesAPI = (props: MaybeRef<{ hotelID: number; roomID?: number }>) => {
  const { hotelID, roomID } = unref(props)
  return useAdminAPI(`/hotels/${hotelID}/images/${roomID}/list`, {
    beforeFetch(ctx) {
      if (roomID === undefined) ctx.cancel()
    },
  })
    .get()
    .json<RoomImage[]>()
}

export const useHotelRoomAPI = (props: MaybeRef<{ hotelID: number; roomID?: number }>) => {
  const { hotelID, roomID } = unref(props)
  return useAdminAPI(`/hotels/${hotelID}/rooms/${roomID}/get`, {
    beforeFetch(ctx) {
      if (roomID === undefined) ctx.cancel()
    },
  })
    .get()
    .json<Room>()
}

type HotelImagesUploadProps = {
  hotelID: number
  roomID?: number
  filesForm: HTMLFormElement
}
export const useHotelImagesUploadAPI = (props: MaybeRef<HotelImagesUploadProps>) => {
  const { hotelID, roomID, filesForm } = unref(props)
  const formData = new FormData(filesForm)
  if (roomID !== undefined) {
    formData.append('room_id', String(roomID))
  }
  return useAdminAPI(`/hotels/${hotelID}/images/upload`)
    .post(formData)
}

export const useHotelImageRemoveAPI = (props: MaybeRef<{ hotelID: number; imageID: number | null }>) => {
  const getProps = () => unref(props)
  return useAdminAPI(computed(() => {
    const { hotelID, imageID } = getProps()
    return `/hotels/${hotelID}/images/${imageID}`
  }), {
    beforeFetch(ctx) {
      const { imageID } = getProps()
      if (imageID === null) ctx.cancel()
    },
  }).delete()
    .json<{ success: boolean }>()
}

type HotelImagesReorderProps = { hotelID: number; images: HotelImage[] }
export const useHotelImagesReorderAPI = (props: MaybeRef<HotelImagesReorderProps>) => {
  const { hotelID, images } = unref(props)
  // @todo отправить запрос на пересортировку
  return useAdminAPI(`/hotels/${hotelID}/images/reorder`)
    .post(images)
}

type HotelRoomImageProps = { hotelID: number; roomID: number; imageID: number }

export const useHotelRoomImageCreateAPI = (props: MaybeRef<HotelRoomImageProps>) => {
  const { hotelID, roomID, imageID } = unref(props)
  return useAdminAPI(`/hotels/${hotelID}/rooms/${roomID}/images/${imageID}/set`)
    .post()
}

export const useHotelRoomImageDeleteAPI = (props: MaybeRef<HotelRoomImageProps>) => {
  const { hotelID, roomID, imageID } = unref(props)
  return useAdminAPI(`/hotels/${hotelID}/rooms/${roomID}/images/${imageID}/unset`)
    .post()
}

export const useHotelContractGetAPI = (props: MaybeRef<{ hotelID: number; contractID: number }>) => {
  const { hotelID, contractID } = unref(props)
  return useAdminAPI(`/hotels/${hotelID}/contracts/${contractID}/get`)
    .get()
    .json<Contract>()
}
