import { computed, unref } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { getURL, useAdminAPI } from '~resources/lib/api/index'
import { City } from '~resources/lib/models'

export const useCitySearchAPI = (props: MaybeRef<{ countryID: number }>) =>
  useAdminAPI(computed(() => getURL('/cities/search', unref(props))))
    .get()
    .json<City[]>()
