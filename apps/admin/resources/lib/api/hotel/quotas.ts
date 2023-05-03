import { computed } from 'vue'

import { AfterFetchContext, MaybeRef } from '@vueuse/core'

import { alternateDataAfterFetch, useAdminAPI } from '~resources/lib/api'
import { HotelRoomResponse } from '~resources/lib/api/hotel/room'
import { getRef } from '~resources/lib/vue'

export type MonthNumber = 1 | 2 | 3 | 4 | 5 | 6 | 7 | 8 | 9 | 10 | 11 | 12

// https://www.php.net/manual/en/dateinterval.format.php
export type QueryInterval =
  | 'P1M' // 1 month
  | 'P3M' // 3 months
  | 'P6M' // 6 months
  | 'P1Y' // 1 year
  | 'P2Y' // 2 years
  | 'P3Y' // 3 years

type HotelRoomQuotaProps = {
  hotelID: number
  month: MonthNumber
  year: number
  interval: QueryInterval
  roomID?: number
  availability?: 'sold' | 'stopped' | 'available'
}

type HotelRoomQuotaPayload = Omit<HotelRoomQuotaProps, 'hotelID'>

export type HotelQuotaID = number

export type QuotaStatus = 0 | 1

export type HotelQuotaResponse = {
  id: HotelQuotaID
  // Y-m-d
  date: string
  room_id: HotelRoomResponse['id']
  // 0 = closed, 1 = opened
  status: QuotaStatus
  release_days: number
  count_available: number
  count_booked: number
  count_reserved: number
}

export type HotelQuota = Omit<HotelQuotaResponse, 'room_id'> & {
  roomID: number
}

type HotelQuotasResponse = HotelQuotaResponse[]
type UseHotelQuota = HotelQuota[] | null

export const useHotelQuotasAPI = (props: MaybeRef<HotelRoomQuotaProps>) =>
  useAdminAPI(computed(() => getRef(props, ({ hotelID }) =>
    `/hotels/${hotelID}/quotas`)), {
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
      month,
      year,
      interval,
      availability,
    }) => ({
      room_id: roomID,
      month,
      year,
      interval,
      availability,
    }))))
    .json<UseHotelQuota>()
