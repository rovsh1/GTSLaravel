import { unref } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { getURL, useAdminAPI } from '~resources/lib/api/index'
import { City } from '~resources/lib/models'

export const useSearchCititesAPI = (props: MaybeRef<{ countryId: number }>) => useAdminAPI(getURL('/cities/search', unref(props)))
  .get()
  .json<City[]>()
