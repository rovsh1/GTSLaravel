import { computed } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { getNullableRef } from '~resources/vue/vue'

import { APIDate, BaseResponse, useAdminAPI } from '~api'

import { HotelRoomQuotasUpdateBaseProps } from '.'

export type HotelRoomQuotasStatusUpdateKind = 'open' | 'close' | 'reset'

export type HotelRoomQuotasStatusUpdateProps = HotelRoomQuotasUpdateBaseProps & {
  kind: HotelRoomQuotasStatusUpdateKind
}

type HotelRoomQuotasUpdateStatusPayload = {
  dates: APIDate[]
}

export const useHotelRoomQuotasStatusUpdate = (props: MaybeRef<HotelRoomQuotasStatusUpdateProps | null>) =>
  useAdminAPI(props, ({ roomID, kind }) =>
    `/quotas/rooms/${roomID}/quota/${kind}`)
    .put(computed<string>(() => JSON.stringify(
      getNullableRef<HotelRoomQuotasStatusUpdateProps, HotelRoomQuotasUpdateStatusPayload>(
        props,
        ({ dates }) => ({ dates }),
      ),
    )), 'application/json')
    .json<BaseResponse>()
