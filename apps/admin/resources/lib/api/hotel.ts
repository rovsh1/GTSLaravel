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
  images: File[] | null
}
export const useHotelImagesUploadAPI = (props: MaybeRef<HotelImagesUploadProps>) => {
  const getProps = () => unref(props)
  const url = computed(() => {
    const { hotelID } = getProps()
    return `/hotels/${hotelID}/images/upload`
  })
  const payload = computed(() => {
    const { roomID, images } = getProps()
    const formData = new FormData()
    images?.forEach((image) => {
      formData.append('files[]', image)
    })
    if (roomID !== undefined) {
      formData.append('room_id', String(roomID))
    }
    return formData
  })
  return useAdminAPI(url.value, {
    beforeFetch(ctx) {
      const { images } = unref(props)
      console.log({ images })
      if (images === null) ctx.cancel()
    },
  })
    .post(payload)
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

type HotelImagesReorderProps = { hotelID: number; imagesIDs: HotelImage['id'][] | null }
export const useHotelImagesReorderAPI = (props: MaybeRef<HotelImagesReorderProps>) =>
  useAdminAPI(computed(() => {
    const { hotelID } = unref(props)
    return `/hotels/${hotelID}/images/reorder`
  }), {
    beforeFetch(ctx) {
      if (unref(props).imagesIDs === null) ctx.cancel()
    },
  })
    .post(computed(() => ({ indexes: unref(props).imagesIDs ?? [] })))
    .json<{ success: boolean }>()

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
