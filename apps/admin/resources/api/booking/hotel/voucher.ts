import { computed } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { DateResponse, useAdminAPI } from '~api'
import { BookingID } from '~api/booking/models'

import { getNullableRef } from '~lib/vue'

export interface BookingVoucherPayload {
  bookingID: BookingID
}

export interface BookingVoucher {
  id: number
  dateCreate: DateResponse
}

export interface BookingVoucherSendResponse {
  isExternalNumberRequired: boolean
}

export const useBookingVoucherListAPI = (props: MaybeRef<BookingVoucherPayload>) =>
  useAdminAPI(props, ({ bookingID }) => `/hotel-booking/${bookingID}/voucher/list`)
    .get()
    .json<BookingVoucher[]>()

export const sendBookingVoucher = (props: MaybeRef<BookingVoucherPayload>) =>
  useAdminAPI(
    props,
    ({ bookingID }) => `/hotel-booking/${bookingID}/voucher`,
    { immediate: true },
  )
    .post(computed<string>(() => JSON.stringify(
      getNullableRef<BookingVoucherPayload, any>(
        props,
        (): any => null,
      ),
    )), 'application/json')
    .json<BookingVoucherSendResponse>()
