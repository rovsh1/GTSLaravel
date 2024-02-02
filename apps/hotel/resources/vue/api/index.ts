import { computed, unref } from 'vue'

import { AfterFetchContext, createFetch, MaybeRef, useFetch, UseFetchOptions } from '@vueuse/core'
import qs from 'qs'

import { ADMIN_API_URL } from '~resources/js/config/env'
import { getNullableRef, RefGetter } from '~resources/vue/vue'

import { handleAjaxError } from '~helpers/ajax-error'

export type DateResponse = string

export type APIDate = string

export type BaseResponse = {
  success: boolean
  data: []
}

const useAPI = (base: string) => <T>(
  props: MaybeRef<T | null>,
  urlGetter: RefGetter<T, string>,
  options?: UseFetchOptions,
) => {
  const api: typeof useFetch = createFetch({
    baseUrl: base,
    options: {
      immediate: false,
      beforeFetch: (ctx) => {
        if (unref(props) === null) {
          ctx.cancel()
        }
        ctx.options.headers = {
          ...ctx.options.headers,
          Accept: 'application/json',
        }
      },
      onFetchError: (ctx) => {
        handleAjaxError(ctx)
        return ctx
      },
    },
  })
  const url = computed(() =>
    getNullableRef(props, urlGetter, ''))
  return api(url, { ...options })
}

export const useAdminAPI = useAPI(ADMIN_API_URL)

export const getURL = (url: string, query?: Record<string, string | number>): string =>
  (query === undefined ? url : `${url}?${qs.stringify(query)}`)

const useAlternateFetchData = <Response, Data>(ctx: AfterFetchContext<Response>) =>
  (data: Data): AfterFetchContext<Data> => ({
    response: ctx.response,
    data,
  })

export const alternateDataAfterFetch = <Response, Altered>(
  ctx: AfterFetchContext<Response>,
  transform: (data: Response) => Altered,
): AfterFetchContext | AfterFetchContext<Altered> => {
  if (ctx.data === null) return ctx
  const alter = useAlternateFetchData<Response, Altered>(ctx)
  return alter(transform(ctx.data))
}