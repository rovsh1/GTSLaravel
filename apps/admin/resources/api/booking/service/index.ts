import { computed, unref } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { BaseResponse, useAdminAPI } from '~api'
import { BaseBooking, BookingID } from '~api/booking/models'
import { Car } from '~api/supplier/cars'

import { getNullableRef } from '~lib/vue'

export type ServiceType = {
  id: number
  name: string
}

export type AirportInfo = {
  id: number
  name: string
}

export type RailwayStationInfo = {
  id: number
  name: string
}

export type ServiceInfo = ServiceType & {
  supplierId: number
}

export type CarBid = {
  id: number
  carInfo: Car
  carsCount: number
  passengersCount: number
  baggageCount: number
  babyCount: number
}

export interface BookingDetailsType {
  id: number
  system_name: string
  display_name: string
}

export type Booking = {
  details?: any
  serviceType: ServiceType
} & BaseBooking

export interface GetBookingPayload {
  bookingID: BookingID
}

export interface UpdateBookingStatusPayload {
  bookingID: BookingID
  status: number
  notConfirmedReason?: string
  cancelFeeAmount?: number
  clientCancelFeeAmount?: number
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

export interface UpdateBookingDetailsPayload {
  bookingID: BookingID
  field: string
  value: any
}

export const useGetBookingAPI = (props: MaybeRef<GetBookingPayload>) =>
  useAdminAPI(props, ({ bookingID }) => `/service-booking/${bookingID}/get`)
    .get()
    .json<Booking>()

export const updateBookingStatus = (props: MaybeRef<UpdateBookingStatusPayload>) =>
  useAdminAPI(
    props,
    ({ bookingID }) => `/service-booking/${bookingID}/status/update`,
    { immediate: true },
  )
    .put(computed<string>(() => JSON.stringify(
      getNullableRef<UpdateBookingStatusPayload, any>(
        props,
        (payload: UpdateBookingStatusPayload): any => ({
          status: payload.status,
          not_confirmed_reason: payload.notConfirmedReason,
          net_penalty: payload.cancelFeeAmount,
          gross_penalty: payload.clientCancelFeeAmount,
        }),
      ),
    )), 'application/json')
    .json<UpdateBookingStatusResponse>()

export const updateExternalNumber = (props: MaybeRef<UpdateExternalNumberPayload>) =>
  useAdminAPI(
    props,
    ({ bookingID }) => `/service-booking/${bookingID}/external/number`,
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
    ({ bookingID }) => `/service-booking/${bookingID}/note`,
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
    ({ bookingID }) => `/service-booking/${bookingID}/manager`,
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
  form.action = `/service-booking/${payload.bookingID}/copy`
  form.submit()
}

export const updateBookingDetails = (props: MaybeRef<UpdateBookingDetailsPayload>) =>
  useAdminAPI(
    props,
    ({ bookingID }) => `/service-booking/${bookingID}/details`,
    { immediate: true },
  )
    .put(computed<string>(() => JSON.stringify(
      getNullableRef<UpdateBookingDetailsPayload, any>(
        props,
        (payload: UpdateBookingDetailsPayload): any => ({
          field: payload.field,
          value: payload.value,
        }),
      ),
    )), 'application/json')
    .json<UpdateBookingStatusResponse>()

export const useGetBookingDetailsTypesAPI = (props?: any) =>
  useAdminAPI(props, () => '/service-booking/details/types')
    .get()
    .json<BookingDetailsType[]>()
