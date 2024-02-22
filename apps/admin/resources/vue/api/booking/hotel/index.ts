import { computed, unref } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { getNullableRef } from '~resources/vue/vue'

import { BaseResponse, useAdminAPI } from '~api'
import { HotelBookingDetails } from '~api/booking/hotel/details'
import { BaseBooking, BookingID } from '~api/booking/models'

export type Booking = {
  details: HotelBookingDetails
} & BaseBooking

export interface GetBookingPayload {
  bookingID: BookingID
}

export interface UpdateBookingStatusPayload {
  bookingID: BookingID
  status: number
  notConfirmedReason?: string
  cancelFeeAmount?: number
}

export interface UpdateExternalNumberPayload {
  bookingID: BookingID
  type: number
  number?: string | null
}

export interface UpdateNotePayload {
  bookingID: BookingID
  note?: string
}

export interface UpdateManagerPayload {
  bookingID: BookingID
  managerId: number | `${number}`
}

export interface UpdateBookingStatusResponse {
  isNotConfirmedReasonRequired: boolean
  isCancelFeeAmountRequired: boolean
}

export interface CopyBookingPayload {
  bookingID: BookingID
}

export const useGetBookingAPI = (props: MaybeRef<GetBookingPayload>) =>
  useAdminAPI(props, ({ bookingID }) => `/hotel-booking/${bookingID}/get`)
    .get()
    .json<Booking>()

export const updateBookingStatus = (props: MaybeRef<UpdateBookingStatusPayload>) =>
  useAdminAPI(
    props,
    ({ bookingID }) => `/hotel-booking/${bookingID}/status/update`,
    { immediate: true },
  )
    .put(computed<string>(() => JSON.stringify(
      getNullableRef<UpdateBookingStatusPayload, any>(
        props,
        (payload: UpdateBookingStatusPayload): any => ({
          status: payload.status,
          not_confirmed_reason: payload.notConfirmedReason,
          net_penalty: payload.cancelFeeAmount,
        }),
      ),
    )), 'application/json')
    .json<UpdateBookingStatusResponse>()

export const updateExternalNumber = (props: MaybeRef<UpdateExternalNumberPayload>) =>
  useAdminAPI(
    props,
    ({ bookingID }) => `/hotel-booking/${bookingID}/external/number`,
    { immediate: true },
  )
    .put(computed<string>(() => JSON.stringify(
      getNullableRef<UpdateExternalNumberPayload, any>(
        props,
        (payload: UpdateExternalNumberPayload): any => ({
          type: payload.type,
          number: payload.number,
        }),
      ),
    )), 'application/json')
    .json<BaseResponse>()

export const updateNote = (props: MaybeRef<UpdateNotePayload>) =>
  useAdminAPI(
    props,
    ({ bookingID }) => `/hotel-booking/${bookingID}/note`,
    { immediate: true },
  )
    .put(computed<string>(() => JSON.stringify(
      getNullableRef<UpdateNotePayload, any>(
        props,
        (payload: UpdateNotePayload): any => ({
          note: payload.note,
        }),
      ),
    )), 'application/json')
    .json<BaseResponse>()

export const updateManager = (props: MaybeRef<UpdateManagerPayload>) =>
  useAdminAPI(
    props,
    ({ bookingID }) => `/hotel-booking/${bookingID}/manager`,
    { immediate: true },
  )
    .put(computed<string>(() => JSON.stringify(
      getNullableRef<UpdateManagerPayload, any>(
        props,
        (payload: UpdateManagerPayload): any => ({
          manager_id: payload.managerId,
        }),
      ),
    )), 'application/json')
    .json<BaseResponse>()

export const copyBooking = (props: MaybeRef<CopyBookingPayload>) => {
  const payload = unref(props)
  const form = document.createElement('form')
  document.body.appendChild(form)
  form.method = 'post'
  form.action = `/hotel-booking/${payload.bookingID}/copy`
  form.submit()
}
