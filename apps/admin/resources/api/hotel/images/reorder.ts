import { computed } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { BaseResponse, useAdminAPI } from '~api'

import { getNullableRef } from '~lib/vue'

import { HotelImageID } from '.'

type HotelImagesReorderProps = {
  hotelID: number
  imagesIDs: HotelImageID[]
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
    .json<BaseResponse>()
