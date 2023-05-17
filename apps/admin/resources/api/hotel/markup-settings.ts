import { computed } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { BaseResponse, useAdminAPI } from '~api'

import { getNullableRef } from '~lib/vue'

export type Percent = number
export type Time = string
export type DateTime = string
export type CancelPeriodType = number

export type ClientMarkups = {
  individual: Percent
  TA: Percent
  OTA: Percent
  TO: Percent
}

export type MarkupCondition = {
  from: Time
  to: Time
  percent: Percent
}

export type NoCheckInMarkup = {
  percent: Percent
  cancelPeriodType: CancelPeriodType
}

export type DailyMarkup = {
  percent: Percent
  cancelPeriodType: CancelPeriodType
  daysCount: number
}

export type CancelPeriod = {
  from: DateTime
  to: DateTime
  noCheckInMarkup: NoCheckInMarkup
  dailyMarkups: DailyMarkup[]
}

export type MarkupSettings = {
  vat: Percent
  touristTax: Percent
  clientMarkups: ClientMarkups
  earlyCheckIn: MarkupCondition[]
  lateCheckOut: MarkupCondition[]
  cancelPeriods: CancelPeriod[]
}

type HotelMarkupSettingsProps = {
  hotelID: number
}

export type HotelMarkupSettingsUpdateProps = {
  hotelID: number
  key: string
  value: string | number | MarkupCondition | DailyMarkup
}

export type HotelMarkupSettingsConditionAddProps = {
  hotelID: number
  key: string
  value: MarkupCondition | DailyMarkup
}

export type HotelMarkupSettingsConditionDeleteProps = {
  hotelID: number
  key: string
  index: number
}

export const useHotelMarkupSettingsAPI = (props: MaybeRef<HotelMarkupSettingsProps | null>) =>
  useAdminAPI(props, ({ hotelID }) => `/hotels/${hotelID}/settings/markup`)
    .get()
    .json<MarkupSettings>()

export const updateConditionHotelMarkupSettings = (props: MaybeRef<HotelMarkupSettingsUpdateProps | null>) =>
  useAdminAPI(
    props,
    ({ hotelID }) => `/hotels/${hotelID}/settings/markup`,
    { immediate: true },
  )
    .put(computed<string>(() => JSON.stringify(
      getNullableRef<HotelMarkupSettingsUpdateProps, any>(
        props,
        (payload: HotelMarkupSettingsUpdateProps): any => ({ key: payload.key, value: payload.value }),
      ),
    )), 'application/json')
    .json<BaseResponse>()

export const addConditionHotelMarkupSettings = (props: MaybeRef<HotelMarkupSettingsConditionAddProps | null>) =>
  useAdminAPI(
    props,
    ({ hotelID }) => `/hotels/${hotelID}/settings/markup/condition`,
    { immediate: true },
  )
    .post(computed<string>(() => JSON.stringify(
      getNullableRef<HotelMarkupSettingsConditionAddProps, any>(
        props,
        (payload: HotelMarkupSettingsConditionAddProps): any => ({ key: payload.key, value: payload.value }),
      ),
    )), 'application/json')
    .json<BaseResponse>()

export const deleteConditionHotelMarkupSettings = (props: MaybeRef<HotelMarkupSettingsConditionDeleteProps | null>) =>
  useAdminAPI(
    props,
    ({ hotelID }) => `/hotels/${hotelID}/settings/markup/condition`,
    { immediate: true },
  )
    .delete(computed<string>(() => JSON.stringify(
      getNullableRef<HotelMarkupSettingsConditionDeleteProps, any>(
        props,
        (payload: HotelMarkupSettingsConditionDeleteProps): any => ({ key: payload.key, index: payload.index }),
      ),
    )), 'application/json')
    .json<BaseResponse>()
