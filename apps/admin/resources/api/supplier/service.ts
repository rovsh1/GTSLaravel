import { computed } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { BaseResponse, useAdminAPI } from '~api'

import { getNullableRef } from '~lib/vue'

type ServiceDetailsPayload = {
  airportId?: number
  cityId?: number
  fromCityId?: number
  toCityId?: number
  returnTripIncluded?: boolean
  railwayStationId?: number
}

export type CreateServicePayload = {
  supplierId: number
  title: string
  type: number
  data: ServiceDetailsPayload
}

export type UpdateServicePayload = CreateServicePayload & {
  serviceId: number
}

export const createService = (props: MaybeRef<CreateServicePayload>) =>
  useAdminAPI(
    props,
    ({ supplierId }) => `/supplier/${supplierId}/services`,
    { immediate: true },
  )
    .post(computed<string>(() => JSON.stringify(
      getNullableRef<CreateServicePayload, any>(
        props,
        (payload: CreateServicePayload): any => ({
          data: {
            supplier_id: payload.supplierId,
            title: payload.title,
            type: payload.type,
            data: {
              ...payload.data,
            },
          },
        }),
      ),
    )), 'application/json')
    .json<BaseResponse>()

export const updateService = (props: MaybeRef<UpdateServicePayload>) =>
  useAdminAPI(
    props,
    ({ supplierId, serviceId }) => `/supplier/${supplierId}/services/${serviceId}`,
    { immediate: true },
  )
    .post(computed<string>(() => JSON.stringify(
      getNullableRef<UpdateServicePayload, any>(
        props,
        (payload: UpdateServicePayload): any => ({
          data: {
            supplier_id: payload.supplierId,
            title: payload.title,
            type: payload.type,
            data: {
              ...payload.data,
            },
          },
        }),
      ),
    )), 'application/json')
    .json<BaseResponse>()
