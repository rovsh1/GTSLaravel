import { MaybeRef } from '@vueuse/core'

import { useAdminAPI } from '~resources/lib/api'

type HotelImageRemoveProps = {
  hotelID: number
  imageID: number
}

export const useHotelImageRemoveAPI = (props: MaybeRef<HotelImageRemoveProps | null>) =>
  useAdminAPI(props, ({ hotelID, imageID }) =>
    `/hotels/${hotelID}/images/${imageID}`)
    .delete()
    .json<{ success: boolean }>()
