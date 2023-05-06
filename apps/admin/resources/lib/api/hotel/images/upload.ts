import { computed, unref } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { useAdminAPI } from '~lib/api'

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
