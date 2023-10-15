import { computed } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { BaseResponse, useAdminAPI } from '~api'

import { getNullableRef } from '~lib/vue'

export type Car = {
  id: number
  orderId: number
  carModel: string
  carCount: number
  passengerCount: number
  baggageCount: number
}

export interface AddOrderCarPayload {
  orderId: number
  countryId: number
  fullName: string
  gender: number
  isAdult: boolean
  age?: number | null
  hotelBookingId?: number
  hotelBookingRoomId?: number
  airportBookingId?: number
}

export interface UpdateOrderCarPayload {
  orderId: number
  guestId: number
  countryId: number
  fullName: string
  gender: number
  isAdult: boolean
  age?: number | null
}

export interface DeleteOrderCarPayload {
  orderId: number
  guestId: number
}

export interface AddOrderCarResponse extends Car {

}

export const useGetOrderGuestsAPI = ({ orderId }: { orderId: number }) =>
  useAdminAPI({ orderId }, () => `/booking-order/${orderId}/guests`)
    .get()
    .json<Car[]>()

export const addOrderGuest = (props: MaybeRef<AddOrderCarPayload | null>) =>
  useAdminAPI(
    props,
    ({ orderId }) => `/booking-order/${orderId}/guests/add`,
    { immediate: true },
  )
    .post(computed<string>(() => JSON.stringify(
      getNullableRef<AddOrderCarPayload, any>(
        props,
        (payload: AddOrderCarPayload): any => ({
          full_name: payload.fullName,
          country_id: payload.countryId,
          gender: payload.gender,
          is_adult: payload.isAdult,
          age: payload.age,
          hotelBookingId: payload.hotelBookingId,
          hotelBookingRoomId: payload.hotelBookingRoomId,
          airportBookingId: payload.airportBookingId,
        }),
      ),
    )), 'application/json')
    .json<AddOrderCarResponse>()

export const updateOrderGuest = (props: MaybeRef<UpdateOrderCarPayload | null>) =>
  useAdminAPI(
    props,
    ({ orderId, guestId }) => `/booking-order/${orderId}/guests/${guestId}`,
    { immediate: true },
  )
    .put(computed<string>(() => JSON.stringify(
      getNullableRef<UpdateOrderCarPayload, any>(
        props,
        (payload: UpdateOrderCarPayload): any => ({
          full_name: payload.fullName,
          country_id: payload.countryId,
          gender: payload.gender,
          is_adult: payload.isAdult,
          age: payload.age,
        }),
      ),
    )), 'application/json')
    .json<BaseResponse>()

export const deleteOrderGuest = (props: MaybeRef<DeleteOrderCarPayload | null>) =>
  useAdminAPI(
    props,
    ({ orderId, guestId }) => `/booking-order/${orderId}/guests/${guestId}`,
    { immediate: true },
  )
    .delete(undefined, 'application/json')
    .json<BaseResponse>()
