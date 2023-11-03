import { computed } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { useAdminAPI } from '~api'

import { getNullableRef } from '~lib/vue'

export type DeleteServicePayload = {
  supplierId: number
  serviceId: number
}

type DeleteServiceResponse = {
  action: string
  url: string
}

export const deleteService = (props: MaybeRef<DeleteServicePayload>) =>
  useAdminAPI(
    props,
    ({ supplierId, serviceId }) => `/supplier/${supplierId}/services/${serviceId}`,
    { immediate: true },
  )
    .post(computed<string>(() => JSON.stringify(
      getNullableRef<DeleteServicePayload, any>(
        props,
        (): any => ({
          _method: 'delete',
        }),
      ),
    )), 'application/json')
    .json<DeleteServiceResponse>()