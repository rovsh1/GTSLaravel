import { computed } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { BaseResponse, getURL, useAdminAPI } from '~api'

import { getNullableRef } from '~lib/vue'

interface RoomSeasonsPricesProps {
  hotelID: number
}

export interface RoomSeasonPrice {
  season_id: number
  room_id: number
  rate_id: number
  is_resident: boolean
  guests_count: number
  price: number
  date?: string
}

export interface UpdateRoomSeasonPricePayload {
  hotelID: number
  seasonID: number
  roomID: number
  rateID: number
  guestsCount: number
  isResident?: boolean
  price: number
}

export const useRoomSeasonsPricesListAPI = (props: MaybeRef<RoomSeasonsPricesProps | null>) =>
  useAdminAPI(props, ({ hotelID }) =>
    getURL(`/hotels/${hotelID}/seasons/prices`))
    .get()
    .json<RoomSeasonPrice[]>()

export const updateRoomSeasonPrice = (props: MaybeRef<UpdateRoomSeasonPricePayload>) =>
  useAdminAPI(
    props,
    ({ hotelID, seasonID }) => `/hotels/${hotelID}/seasons/${seasonID}/prices`,
    { immediate: true },
  )
    .put(computed<string>(() => JSON.stringify(
      getNullableRef<UpdateRoomSeasonPricePayload, any>(
        props,
        (payload: UpdateRoomSeasonPricePayload): any => ({
          room_id: payload.roomID,
          rate_id: payload.rateID,
          guests_count: payload.guestsCount,
          is_resident: payload.isResident,
          price: payload.price,
        }),
      ),
    )), 'application/json')
    .json<BaseResponse>()
