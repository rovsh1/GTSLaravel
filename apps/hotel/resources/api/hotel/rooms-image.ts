import { AfterFetchContext, MaybeRef } from '@vueuse/core'

import { alternateDataAfterFetch, useAdminAPI } from '~api'
import { HotelRoomImage, HotelRoomImageResponse, mapHotelRoomsImageResponseToHotelRoomsImage } from '~api/hotel'

type HotelRoomsImageResponse = HotelRoomImageResponse[]

type HotelRoomsListWithAttachedImageProps = {
  imageID: number
}

export type UseHotelRoomsImage = HotelRoomImage[] | null

export const useHotelRoomsListWithAttachedImageAPI = (props: MaybeRef<HotelRoomsListWithAttachedImageProps | null>) => useAdminAPI(props, ({ imageID }) =>
  `/photos/${imageID}/rooms`, {
  afterFetch: (ctx: AfterFetchContext<HotelRoomsImageResponse>) =>
    alternateDataAfterFetch<HotelRoomsImageResponse, UseHotelRoomsImage>(ctx, (data) =>
      (data.length > 0 ? data.map(mapHotelRoomsImageResponseToHotelRoomsImage) : null)),
})
  .get()
  .json<UseHotelRoomsImage>()
