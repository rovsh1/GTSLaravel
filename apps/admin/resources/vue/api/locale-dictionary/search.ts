import { AfterFetchContext, MaybeRef } from '@vueuse/core'

import { alternateDataAfterFetch, useAdminAPI } from '~api'

export interface LocaleDictionaryResponse {
  key: string
  value_ru: string
  value_en: string
  value_uz: string
}

export type LocaleDictionary = {
  key: string
  valueRu: string
  valueEn: string
  valueUz: string
}

type LocaleDictionarySearchProp = {
  searchQuery: string
}

type LocalesDictionary = LocaleDictionary[]

type LocalesDictionaryResponse = LocaleDictionaryResponse[]

export const useLocaleDictionaryAPI = (props: MaybeRef<LocaleDictionarySearchProp>) =>
  useAdminAPI(props, ({ searchQuery }) =>
    `/locale-dictionary/search?term=${searchQuery}`, {
    afterFetch: (ctx: AfterFetchContext<LocalesDictionaryResponse>) =>
      alternateDataAfterFetch<LocalesDictionaryResponse, LocalesDictionary>(ctx, (data) =>
        (data.length > 0 ? data.map(({
          key,
          value_en: valueEn,
          value_ru: valueRu,
          value_uz: valueUz,
        }) => ({
          key,
          valueEn,
          valueRu,
          valueUz,
        })) : [])),
  })
    .get()
    .json<LocalesDictionary>()
