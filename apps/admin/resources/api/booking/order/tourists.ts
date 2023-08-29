import { computed } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { BaseResponse, useAdminAPI } from '~api'

import { getNullableRef } from '~lib/vue'

export type Tourist = {
  id: number
  orderId: number
  countryId: number
  fullName: string
  gender: number
  isAdult: boolean
  age?: number | null
}

export interface AddOrderTouristPayload {
  orderId: number
  countryId: number
  fullName: string
  gender: number
  isAdult: boolean
  age?: number | null
}

export interface UpdateOrderTouristPayload {
  orderId: number
  touristId: number
  countryId: number
  fullName: string
  gender: number
  isAdult: boolean
  age?: number | null
}

export interface DeleteOrderTouristPayload {
  orderId: number
  touristId: number
}

export interface AddOrderTouristResponse extends Tourist {

}

export const useGetOrderTouristsAPI = ({ orderId }: { orderId: number }) =>
  useAdminAPI({ orderId }, () => `/booking-order/${orderId}/tourists`)
    .get()
    .json<Tourist[]>()

export const addOrderTourist = (props: MaybeRef<AddOrderTouristPayload | null>) =>
  useAdminAPI(
    props,
    ({ orderId }) => `/booking-order/${orderId}/tourists/add`,
    { immediate: true },
  )
    .post(computed<string>(() => JSON.stringify(
      getNullableRef<AddOrderTouristPayload, any>(
        props,
        (payload: AddOrderTouristPayload): any => ({
          full_name: payload.fullName,
          country_id: payload.countryId,
          gender: payload.gender,
          is_adult: payload.isAdult,
          age: payload.age,
        }),
      ),
    )), 'application/json')
    .json<AddOrderTouristResponse>()

export const updateOrderTourist = (props: MaybeRef<UpdateOrderTouristPayload | null>) =>
  useAdminAPI(
    props,
    ({ orderId, touristId }) => `/booking-order/${orderId}/tourists/${touristId}`,
    { immediate: true },
  )
    .put(computed<string>(() => JSON.stringify(
      getNullableRef<UpdateOrderTouristPayload, any>(
        props,
        (payload: UpdateOrderTouristPayload): any => ({
          full_name: payload.fullName,
          country_id: payload.countryId,
          gender: payload.gender,
          is_adult: payload.isAdult,
          age: payload.age,
        }),
      ),
    )), 'application/json')
    .json<BaseResponse>()

export const deleteOrderTourist = (props: MaybeRef<DeleteOrderTouristPayload | null>) =>
  useAdminAPI(
    props,
    ({ orderId, touristId }) => `/booking-order/${orderId}/tourists/${touristId}`,
    { immediate: true },
  )
    .delete(undefined, 'application/json')
    .json<BaseResponse>()
