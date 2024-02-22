import { computed } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { getNullableRef } from '~resources/vue/vue'

import { BaseResponse, useAdminAPI } from '~api'
import { HotelID } from '~api/hotel/get'
import { Time } from '~api/hotel/markup-settings'

export interface TimeSettings {
  checkInAfter: Time
  checkOutBefore: Time
  breakfastFrom: Time | null
  breakfastTo: Time | null
}

export interface HotelSettings {
  id: HotelID
  name: string
  currency: string
  timeSettings: TimeSettings
}

export const useHotelSettingsAPI = (props: MaybeRef<{ hotelID: number }>) =>
  useAdminAPI(props, ({ hotelID }) =>
    `/hotels/${hotelID}/settings/get`)
    .get()
    .json<HotelSettings>()

export interface UpdateHotelSettingsPayload {
  hotelID: HotelID
  timeSettings: TimeSettings
}

export const updateHotelSettings = (props: MaybeRef<UpdateHotelSettingsPayload>) =>
  useAdminAPI(
    props,
    ({ hotelID }) => `/hotels/${hotelID}/settings`,
    { immediate: true },
  )
    .put(computed<string>(() => JSON.stringify(
      getNullableRef<UpdateHotelSettingsPayload, any>(
        props,
        (payload: UpdateHotelSettingsPayload): any => ({ time_settings: payload.timeSettings }),
      ),
    )), 'application/json')
    .json<BaseResponse>()
