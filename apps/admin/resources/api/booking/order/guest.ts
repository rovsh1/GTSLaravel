import { computed } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { BaseResponse, useAdminAPI } from '~api'

import { getNullableRef } from '~lib/vue'

export type Guest = {
  id: number
  orderId: number
  countryId: number
  fullName: string
  gender: number
  isAdult: boolean
  age?: number | null
}

export interface AddOrderGuestPayload {
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

export interface UpdateOrderGuestPayload {
  orderId: number
  guestId: number
  countryId: number
  fullName: string
  gender: number
  isAdult: boolean
  age?: number | null
}

export interface DeleteOrderGuestPayload {
  orderId: number
  guestId: number
}

export interface AddOrderGuestResponse extends Guest {

}

export const useGetOrderGuestsAPI = ({ orderId }: { orderId: number }) =>
  useAdminAPI({ orderId }, () => `/booking-order/${orderId}/guests`)
    .get()
    .json<Guest[]>()

export const addOrderGuest = (props: MaybeRef<AddOrderGuestPayload | null>) =>
  useAdminAPI(
    props,
    ({ orderId }) => `/booking-order/${orderId}/guests/add`,
    { immediate: true },
  )
    .post(computed<string>(() => JSON.stringify(
      getNullableRef<AddOrderGuestPayload, any>(
        props,
        (payload: AddOrderGuestPayload): any => ({
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
    .json<AddOrderGuestResponse>()

export const updateOrderGuest = (props: MaybeRef<UpdateOrderGuestPayload | null>) =>
  useAdminAPI(
    props,
    ({ orderId, guestId }) => `/booking-order/${orderId}/guests/${guestId}`,
    { immediate: true },
  )
    .put(computed<string>(() => JSON.stringify(
      getNullableRef<UpdateOrderGuestPayload, any>(
        props,
        (payload: UpdateOrderGuestPayload): any => ({
          full_name: payload.fullName,
          country_id: payload.countryId,
          gender: payload.gender,
          is_adult: payload.isAdult,
          age: payload.age,
        }),
      ),
    )), 'application/json')
    .json<BaseResponse>()

export const deleteOrderGuest = (props: MaybeRef<DeleteOrderGuestPayload | null>) =>
  useAdminAPI(
    props,
    ({ orderId, guestId }) => `/booking-order/${orderId}/guests/${guestId}`,
    { immediate: true },
  )
    .delete(undefined, 'application/json')
    .json<BaseResponse>()
