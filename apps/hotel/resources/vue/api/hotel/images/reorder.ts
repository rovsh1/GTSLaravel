import { computed } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { getNullableRef } from '~resources/vue/vue'

import { BaseResponse, useAdminAPI } from '~api'

import { HotelImageID } from '.'

type HotelImagesReorderProps = {
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
  useAdminAPI(props, () =>
    '/photos/reorder')
    .post(computed<string>(() => JSON.stringify(
      getNullableRef<HotelImagesReorderProps, HotelImagesReorderPayload>(
        props,
        ({ imagesIDs }) => ({ indexes: imagesIDs }),
      ),
    )), 'application/json')
    .json<BaseResponse>()

export const useHotelRoomImagesReorderAPI = (props: MaybeRef<HotelRoomImagesReorderProps | null>) =>
  useAdminAPI(props, ({ roomID }) =>
    `/photos/rooms/${roomID}/images/reorder`)
    .post(computed<string>(() => JSON.stringify(
      getNullableRef<HotelRoomImagesReorderProps, HotelRoomImagesReorderPayload>(
        props,
        ({ imagesIDs }) => ({ indexes: imagesIDs }),
      ),
    )), 'application/json')
    .json<BaseResponse>()
