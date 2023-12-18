import { computed } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { BaseResponse, useAdminAPI } from '~api'

import { getNullableRef } from '~lib/vue'

import { HotelImageID } from '.'

type HotelImagesReorderProps = {
  hotelID: number
  imagesIDs: HotelImageID[]
}

type HotelRoomImagesReorderProps = HotelImagesReorderProps & {
  roomID: number
}

type HotelImagesReorderPayload = {
  indexes: number[]
}

type HotelRoomImagesReorderPayload = HotelImagesReorderPayload

export const useHotelImagesReorderAPI = (props: MaybeRef<HotelImagesReorderProps | null>) =>
  useAdminAPI(props, ({ hotelID }) =>
    `/hotels/${hotelID}/images/reorder`)
    .post(computed<string>(() => JSON.stringify(
      getNullableRef<HotelImagesReorderProps, HotelImagesReorderPayload>(
        props,
        ({ imagesIDs }) => ({ indexes: imagesIDs }),
      ),
    )), 'application/json')
    .json<BaseResponse>()

export const useHotelRoomImagesReorderAPI = (props: MaybeRef<HotelRoomImagesReorderProps | null>) =>
  useAdminAPI(props, ({ hotelID, roomID }) =>
    `/hotels/${hotelID}/rooms/${roomID}/images/reorder`)
    .post(computed<string>(() => JSON.stringify(
      getNullableRef<HotelRoomImagesReorderProps, HotelRoomImagesReorderPayload>(
        props,
        ({ imagesIDs }) => ({ indexes: imagesIDs }),
      ),
    )), 'application/json')
    .json<BaseResponse>()
