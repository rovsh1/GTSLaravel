import { computed } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { getNullableRef } from '~resources/vue/vue'

import { BaseResponse, getURL, useAdminAPI } from '~api'

type RoomSeasonsPricesProps = {
  hotelID: number
}

type RoomSeasonsDaysPricesProps = RoomSeasonsPricesProps & {
  seasonID: number
}

export type RoomSeasonPrice = {
  season_id: number
  room_id: number
  rate_id: number
  is_resident: boolean
  guests_count: number
  price: number | null
  has_date_prices: boolean
  date?: string
}

export type UpdateRoomSeasonPricePayload = {
  hotelID: number
  seasonID: number
  roomID: number
  rateID: number
  guestsCount: number
  isResident?: boolean
  price: number | null
}

export type UpdateRoomSeasonPricesBatchPayload = UpdateRoomSeasonPricePayload & {
  date_from: string
  date_to: string
  week_days: number[]
}

export type UpdateRoomSeasonPricesByDayPayload = UpdateRoomSeasonPricePayload & {
  date: string
}

export const useRoomSeasonsPricesListAPI = (props: MaybeRef<RoomSeasonsPricesProps | null>) =>
  useAdminAPI(props, ({ hotelID }) =>
    getURL(`/hotels/${hotelID}/seasons/prices`))
    .get()
    .json<RoomSeasonPrice[]>()

export const useRoomSeasonsDaysPricesListAPI = (props: MaybeRef<RoomSeasonsDaysPricesProps | null>) =>
  useAdminAPI(props, ({ hotelID, seasonID }) =>
    getURL(`/hotels/${hotelID}/seasons/${seasonID}/prices/date`))
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

export const updateRoomSeasonPricesBatch = (props: MaybeRef<UpdateRoomSeasonPricesBatchPayload>) =>
  useAdminAPI(
    props,
    ({ hotelID, seasonID }) => `/hotels/${hotelID}/seasons/${seasonID}/prices/date/batch`,
    { immediate: true },
  )
    .put(computed<string>(() => JSON.stringify(
      getNullableRef<UpdateRoomSeasonPricesBatchPayload, any>(
        props,
        (payload: UpdateRoomSeasonPricesBatchPayload): any => ({
          room_id: payload.roomID,
          rate_id: payload.rateID,
          guests_count: payload.guestsCount,
          is_resident: payload.isResident,
          price: payload.price,
          date_from: payload.date_from,
          date_to: payload.date_to,
          week_days: payload.week_days,
        }),
      ),
    )), 'application/json')
    .json<BaseResponse>()

export const updateRoomSeasonPricesByDay = (props: MaybeRef<UpdateRoomSeasonPricesByDayPayload>) =>
  useAdminAPI(
    props,
    ({ hotelID, seasonID }) => `/hotels/${hotelID}/seasons/${seasonID}/prices/date`,
    { immediate: true },
  )
    .put(computed<string>(() => JSON.stringify(
      getNullableRef<UpdateRoomSeasonPricesByDayPayload, any>(
        props,
        (payload: UpdateRoomSeasonPricesByDayPayload): any => ({
          room_id: payload.roomID,
          rate_id: payload.rateID,
          guests_count: payload.guestsCount,
          is_resident: payload.isResident,
          price: payload.price,
          date: payload.date,
        }),
      ),
    )), 'application/json')
    .json<BaseResponse>()
