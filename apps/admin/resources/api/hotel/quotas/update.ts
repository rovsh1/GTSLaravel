import { computed } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { APIDate, useAdminAPI } from '~api'

import { getNullableRef } from '~lib/vue'

import { HotelRoomQuotasUpdateBaseProps } from '.'

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
