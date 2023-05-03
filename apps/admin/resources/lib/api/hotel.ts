import { computed, unref } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { Contract, Hotel, MonthNumber, QueryInterval, Room } from '~resources/lib/models'
import { getRef } from '~resources/lib/vue'
import { HotelImage, RoomImage } from '~resources/views/hotel/images/models'

import { useAdminAPI } from '.'

export const useHotelAPI = (props: MaybeRef<{ hotelID: number }>) =>
  useAdminAPI(computed(() => getRef(props, ({ hotelID }) =>
    `/hotels/${hotelID}/get`)))
    .get()
    .json<Hotel>()

export const useHotelImagesListAPI = (props: MaybeRef<{ hotelID: number }>) =>
  useAdminAPI(computed(() => getRef(props, ({ hotelID }) =>
    `/hotels/${hotelID}/images/get`)))
    .get()
    .json<HotelImage[]>()

export const useHotelRoomImagesAPI = (props: MaybeRef<{ hotelID: number; roomID?: number }>) =>
  useAdminAPI(computed(() =>
    getRef(props, ({ hotelID, roomID }) =>
      `/hotels/${hotelID}/images/${roomID}/list`)), {
    beforeFetch(ctx) {
      if (getRef(props, ({ roomID }) => roomID) === undefined) ctx.cancel()
    },
  })
    .get()
    .json<RoomImage[]>()

export const useHotelRoomAPI = (props: MaybeRef<{ hotelID: number; roomID?: number }>) =>
  useAdminAPI(computed(() => getRef(props, ({ hotelID, roomID }) =>
    `/hotels/${hotelID}/rooms/${roomID}/get`)), {
    beforeFetch(ctx) {
      if (getRef(props, ({ roomID }) => roomID) === undefined) ctx.cancel()
    },
  })
    .get()
    .json<Room>()

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

type HotelImagesReorderProps = { hotelID: number; imagesIDs: HotelImage['id'][] | null }
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

type HotelRoomImageProps = { hotelID: number; roomID: number; imageID: number }

export const useHotelRoomImageCreateAPI = (props: MaybeRef<HotelRoomImageProps>) =>
  useAdminAPI(computed(() => getRef(props, ({ hotelID, roomID, imageID }) =>
    `/hotels/${hotelID}/rooms/${roomID}/images/${imageID}/set`)))
    .post()

export const useHotelRoomImageDeleteAPI = (props: MaybeRef<HotelRoomImageProps>) =>
  useAdminAPI(getRef(props, ({ hotelID, roomID, imageID }) =>
    `/hotels/${hotelID}/rooms/${roomID}/images/${imageID}/unset`))
    .post()

export const useHotelContractGetAPI = (props: MaybeRef<{ hotelID: number; contractID: number }>) =>
  useAdminAPI(computed(() => getRef(props, ({ hotelID, contractID }) =>
    `/hotels/${hotelID}/contracts/${contractID}/get`)))
    .get()
    .json<Contract>()

export const useHotelRoomsListAPI = (props: MaybeRef<{ hotelID: number }>) =>
  useAdminAPI(computed(() => getRef(props, ({ hotelID }) =>
    `/hotels/${hotelID}/rooms/list`)))
    .get()
    .json<Room[]>()

type HotelRoomQuotaProps = {
  hotelID: number
  month: MonthNumber
  year: number
  interval: QueryInterval
  roomID?: number
  availability?: 'sold' | 'stopped' | 'available'
}
type HotelRoomQuotaPayload = Omit<HotelRoomQuotaProps, 'hotelID'>
export const useHotelQuotasAPI = (props: MaybeRef<HotelRoomQuotaProps>) =>
  useAdminAPI(computed(() => getRef(props, ({ hotelID }) =>
    `/hotels/${hotelID}/quotas`)))
    .post(computed<HotelRoomQuotaPayload>(() => getRef(props, ({
      roomID,
      month,
      year,
      interval,
      availability,
    }) => ({
      room_id: roomID,
      month,
      year,
      interval,
      availability,
    }))))
    .json()
