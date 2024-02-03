import { MaybeRef } from '@vueuse/core'

import { BaseResponse, useAdminAPI } from '~api'

type HotelImageRemoveProps = {
  hotelID: number
  imageID: number
}

export const useHotelImageRemoveAPI = (props: MaybeRef<HotelImageRemoveProps | null>) =>
  useAdminAPI(props, ({ hotelID, imageID }) =>
    `/hotels/${hotelID}/images/${imageID}`)
    .delete()
    .json<BaseResponse>()
