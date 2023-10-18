import { computed } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { BaseResponse, useAdminAPI } from '~api'
import { BookingID } from '~api/booking/models'

import { getNullableRef } from '~lib/vue'

export type DeleteBookingCarPayload = {
  bookingID: BookingID
  id: number
}

export type BookingCarPayload = DeleteBookingCarPayload & {
  carId: number
  carsCount: number
  passengersCount: number
  baggageCount: number
  babyCount: number
}

export const deleteBookingCar = (props: MaybeRef<DeleteBookingCarPayload | null>) =>
  useAdminAPI(
    props,
    ({ bookingID, id }) => `/service-booking/${bookingID}/cars/${id}`,
    { immediate: true },
  )
    .delete({}, 'application/json')
    .json<BaseResponse>()

export const addBookingCar = (props: MaybeRef<BookingCarPayload | null>) =>
  useAdminAPI(
    props,
    ({ bookingID }) => `/service-booking/${bookingID}/cars/add`,
    { immediate: true },
  )
    .post(computed<string>(() => JSON.stringify(
      getNullableRef<BookingCarPayload, any>(props, (payload: BookingCarPayload): any => ({
        carId: payload.carId,
        carsCount: payload.carsCount,
        baggageCount: payload.baggageCount,
        passengersCount: payload.passengersCount,
        babyCount: payload.babyCount,
      })),
    )), 'application/json')
    .json<BaseResponse>()

export const updateBookingCar = (props: MaybeRef<BookingCarPayload | null>) =>
  useAdminAPI(
    props,
    ({ bookingID, id }) => `/service-booking/${bookingID}/cars/${id}`,
    { immediate: true },
  )
    .put(computed<string>(() => JSON.stringify(
      getNullableRef<BookingCarPayload, any>(
        props,
        (payload: BookingCarPayload): any => ({
          carId: payload.carId,
          carsCount: payload.carsCount,
          baggageCount: payload.baggageCount,
          passengersCount: payload.passengersCount,
          babyCount: payload.babyCount,
        }),
      ),
    )), 'application/json')
    .json<BaseResponse>()
