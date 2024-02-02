import { computed } from 'vue'

import { AfterFetchContext, MaybeRef } from '@vueuse/core'

import { getRef } from '~resources/vue/vue'

import { alternateDataAfterFetch, DateResponse, useAdminAPI } from '~api'
import { HotelRoomID } from '~api/hotel'

export type HotelQuotaID = number

export type QuotaStatus = 0 | 1

export type HotelQuotaResponse = {
  id: HotelQuotaID
  date: DateResponse
  room_id: HotelRoomID
  status: QuotaStatus
  release_days: number
  count_available: number
  count_total: number
  count_booked: number
  count_reserved: number
}

type HotelRoomQuotaProps = {
  hotelID: number
  dateFrom: string
  dateTo: string
  roomID?: number
  availability?: 'sold' | 'stopped' | 'available'
}

type HotelRoomQuotaPayload = Omit<HotelRoomQuotaProps, 'hotelID'>

export type HotelQuota = Omit<HotelQuotaResponse, 'room_id'> & {
  roomID: number
}

type HotelQuotasResponse = HotelQuotaResponse[]
export type UseHotelQuota = HotelQuota[] | null

export const useHotelQuotasAPI = (props: MaybeRef<HotelRoomQuotaProps>) =>
  useAdminAPI(props, ({ hotelID }) =>
    `/hotels/${hotelID}/quotas`, {
    afterFetch: (ctx: AfterFetchContext<HotelQuotasResponse>) =>
      alternateDataAfterFetch<HotelQuotasResponse, UseHotelQuota>(ctx, (data) =>
        (data.length === 0
          ? null
          : data.map(({ room_id: roomID, ...rest }) => ({
            roomID: Number(roomID),
            ...rest,
          })))),
  })
    .post(computed<HotelRoomQuotaPayload>(() => getRef(props, ({
      roomID,
      dateFrom,
      dateTo,
      availability,
    }) => ({
      room_id: roomID,
      dateFrom,
      dateTo,
      availability,
    }))))
    .json<UseHotelQuota>()
