import { MaybeRef } from '@vueuse/core'

import { getURL, useAdminAPI } from '~api'

interface HotelRatesProps {
  roomID?: number
}

export interface HotelRate {
  id: number
  name: string
}

export const useHotelRatesAPI = (props: MaybeRef<HotelRatesProps | null>) =>
  useAdminAPI(props, ({ roomID }) =>
    getURL('/hotel/rates/search', { room_id: roomID as number }))
    .get()
    .json<HotelRate[]>()
