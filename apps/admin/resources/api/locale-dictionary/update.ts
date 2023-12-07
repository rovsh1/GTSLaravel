import { computed } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { BaseResponse, useAdminAPI } from '~api'

import { getNullableRef } from '~lib/vue'

type UpdateLocaleDictionaryPayload = {
  key: string
  valueRu: string
  valueEn: string
  valueUz: string
}

export const useUpdateLocaleDictionary = (props: MaybeRef<UpdateLocaleDictionaryPayload | null>) =>
  useAdminAPI(props, () =>
    '/locale-dictionary/update')
    .put(computed<string>(() => JSON.stringify(
      getNullableRef<UpdateLocaleDictionaryPayload, any>(
        props,
        (payload: UpdateLocaleDictionaryPayload): any => ({
          key: payload.key,
          value_ru: payload.valueRu,
          value_en: payload.valueEn,
          value_uz: payload.valueUz,
        }),
      ),
    )), 'application/json')
    .json<BaseResponse>()
