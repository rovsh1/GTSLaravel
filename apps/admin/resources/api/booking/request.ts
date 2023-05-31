import { computed } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { BaseResponse, useAdminAPI } from '~api'
import { BookingID } from '~api/booking/index'

import { getNullableRef } from '~lib/vue'

export interface SendBookingRequestPayload {
  bookingID: BookingID
  type: string
}

export const sendBookingRequest = (props: MaybeRef<SendBookingRequestPayload>) =>
  useAdminAPI(
    props,
    ({ bookingID }) => `/hotel-booking/${bookingID}/request`,
    { immediate: true },
  )
    .post(computed<string>(() => JSON.stringify(
      getNullableRef<SendBookingRequestPayload, any>(
        props,
        (payload: SendBookingRequestPayload): any => ({
          type: payload.type,
        }),
      ),
    )), 'application/json')
    .json<BaseResponse>()
