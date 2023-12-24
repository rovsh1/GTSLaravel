import { computed } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { BaseResponse, useAdminAPI } from '~api'

import { getNullableRef } from '~lib/vue'

type UpdateHotelRoomQuotasBatchPayload = {
  dateFrom: string
  dateTo: string
  weekDays: number[]
  roomIds: number[]
  action: string
}

export const useUpdateHotelRoomQuotasBatch = (props: MaybeRef<UpdateHotelRoomQuotasBatchPayload | null>) =>
  useAdminAPI(props, () =>
    '/quotas/date/batch')
    .put(computed<string>(() => JSON.stringify(
      getNullableRef<UpdateHotelRoomQuotasBatchPayload, any>(
        props,
        (payload: UpdateHotelRoomQuotasBatchPayload): any => ({
          room_ids: payload.roomIds,
          action: payload.action,
          date_from: payload.dateFrom,
          date_to: payload.dateTo,
          week_days: payload.weekDays,
        }),
      ),
    )), 'application/json')
    .json<BaseResponse>()
