import { computed, unref } from 'vue'

import type { AfterFetchContext } from '@vueuse/core'
import { MaybeRef } from '@vueuse/core'

import { Contract, Hotel, HotelRoomResponse, MonthNumber, QueryInterval } from '~resources/lib/models'
import { getRef } from '~resources/lib/vue'
import { HotelImage, RoomImage } from '~resources/views/hotel/images/models'

import { alternateDataAfterFetch, useAdminAPI } from '.'

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
    .json<HotelRoomResponse>()

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

type HotelRoomsResponse = HotelRoomResponse[]

export type HotelRoom = Omit<HotelRoomResponse, 'id'> & {
  id: number
}

export type UseHotelRooms = HotelRoom[] | null

export const useHotelRoomsListAPI = (props: MaybeRef<{ hotelID: number }>) =>
  useAdminAPI(computed(() => getRef(props, ({ hotelID }) =>
    `/hotels/${hotelID}/rooms/list`)), {
    afterFetch: (ctx: AfterFetchContext<HotelRoomsResponse>) =>
      alternateDataAfterFetch<HotelRoomsResponse, UseHotelRooms>(ctx, (data) =>
        (data.length > 0 ? data.map(({ id, ...rest }) => ({
          id: Number(id),
          ...rest,
        })) : null)),
  })
    .get()
    .json<UseHotelRooms>()

type HotelRoomQuotaProps = {
  hotelID: number
  month: MonthNumber
  year: number
  interval: QueryInterval
  roomID?: number
  availability?: 'sold' | 'stopped' | 'available'
}

type HotelRoomQuotaPayload = Omit<HotelRoomQuotaProps, 'hotelID'>

export type HotelQuotaID = number

export type QuotaStatus = 0 | 1

export type HotelQuotaResponse = {
  id: HotelQuotaID
  // Y-m-d
  date: string
  room_id: HotelRoomResponse['id']
  // 0 = closed, 1 = opened
  status: QuotaStatus
  release_days: number
  count_available: number
  count_booked: number
  count_reserved: number
}

export type HotelQuota = Omit<HotelQuotaResponse, 'room_id'> & {
  roomID: number
}

type HotelQuotasResponse = HotelQuotaResponse[]
type UseHotelQuota = HotelQuota[] | null

export const useHotelQuotasAPI = (props: MaybeRef<HotelRoomQuotaProps>) =>
  useAdminAPI(computed(() => getRef(props, ({ hotelID }) =>
    `/hotels/${hotelID}/quotas`)), {
    afterFetch: (ctx: AfterFetchContext<HotelQuotasResponse>) =>
      alternateDataAfterFetch<HotelQuotasResponse, UseHotelQuota>(ctx, (data) =>
        (data.length === 0
          ? null
          : data.map(({ room_id: roomID, ...rest }) => ({
            roomID: Number(roomID),
            ...rest,
          })))),
  })
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
    .json<UseHotelQuota>()
