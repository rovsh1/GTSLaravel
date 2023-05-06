import { computed } from 'vue'

import { AfterFetchContext, MaybeRef } from '@vueuse/core'

import { alternateDataAfterFetch, APIDate, useAdminAPI } from '~resources/lib/api'
import { HotelID } from '~resources/lib/api/hotel/hotel'
import { HotelRoomID, HotelRoomResponse } from '~resources/lib/api/hotel/room'
import { getNullableRef, getRef } from '~resources/lib/vue'

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
  count_total: number
  count_booked: number
  count_reserved: number
}

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

type HotelRoomQuotasUpdateBaseProps = {
  hotelID: HotelID
  roomID: HotelRoomID
  dates: APIDate[]
}

type HotelRoomQuotasCountUpdatePropsKind = {
  kind: 'count'
  count: number
}

type HotelRoomReleaseDaysPropsKind = {
  kind: 'releaseDays'
  releaseDays: number
}

export type HotelRoomQuotasUpdateProps<
  T = HotelRoomQuotasCountUpdatePropsKind | HotelRoomReleaseDaysPropsKind,
> = T & HotelRoomQuotasUpdateBaseProps

export type HotelRoomQuotasCountUpdateProps = HotelRoomQuotasUpdateProps<HotelRoomQuotasCountUpdatePropsKind>
export type HotelRoomReleaseDaysUpdateProps = HotelRoomQuotasUpdateProps<HotelRoomReleaseDaysPropsKind>

type HotelRoomQuotasUpdatePayload = {
  dates: APIDate[]
  count?: number
  release_days?: number
}

export const useHotelRoomQuotasUpdate = (props: MaybeRef<HotelRoomQuotasUpdateProps | null>) =>
  useAdminAPI(props, ({ hotelID, roomID }) =>
    `/hotels/${hotelID}/rooms/${roomID}/quota`)
    .put(computed<string>(() => JSON.stringify(
      getNullableRef<HotelRoomQuotasUpdateProps, HotelRoomQuotasUpdatePayload>(
        props,
        (payload) => {
          const { dates, kind } = payload
          switch (kind) {
            case 'count': {
              const { count } = payload
              return { dates, count }
            }
            case 'releaseDays': {
              const { releaseDays } = payload
              return { dates, release_days: releaseDays }
            }
            default:
              return { dates }
          }
        },
      ),
    )), 'application/json')
    .json<{ success: boolean }>()

export type HotelRoomQuotasStatusUpdateKind = 'open' | 'close' | 'reset'

export type HotelRoomQuotasStatusUpdateProps = HotelRoomQuotasUpdateBaseProps & {
  kind: HotelRoomQuotasStatusUpdateKind
}

type HotelRoomQuotasUpdateStatusPayload = {
  dates: APIDate[]
}

export const useHotelRoomQuotasStatusUpdate = (props: MaybeRef<HotelRoomQuotasStatusUpdateProps | null>) =>
  useAdminAPI(props, ({ hotelID, roomID, kind }) =>
    `/hotels/${hotelID}/rooms/${roomID}/quota/${kind}`)
    .put(computed<string>(() => JSON.stringify(
      getNullableRef<HotelRoomQuotasStatusUpdateProps, HotelRoomQuotasUpdateStatusPayload>(
        props,
        ({ dates }) => ({ dates }),
      ),
    )), 'application/json')
    .json<{ success: boolean }>()
