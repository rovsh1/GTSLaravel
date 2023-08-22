import { computed, unref } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { BaseResponse, useAdminAPI } from '~api'

export type HotelImagesUploadProps = {
  hotelID: number
  roomID?: number
  image: File
}
export const useHotelImagesUploadAPI = (props: MaybeRef<HotelImagesUploadProps | null>) =>
  useAdminAPI(props, ({ hotelID }) =>
    `/hotels/${hotelID}/images/upload`)
    .post(computed<FormData | null>(() => {
      const unwrapped = unref(props)
      if (unwrapped === null) return null
      const { roomID, image } = unwrapped
      const formData = new FormData()
      formData.append('files[]', image)
      if (roomID !== undefined) {
        formData.append('room_id', String(roomID))
      }
      return formData
    }))
    .json<BaseResponse>()
