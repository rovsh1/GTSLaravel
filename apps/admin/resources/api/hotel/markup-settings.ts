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

export const useHotelMarkupSettingsAPI = (props: MaybeRef<HotelMarkupSettingsProps | null>) =>
  useAdminAPI(props, ({ hotelID }) =>
    `/hotels/${hotelID}/settings/markup`)
    .get()
    .json<MarkupSettings>()

export const useHotelMarkupSettingsUpdateAPI = (props: MaybeRef<HotelMarkupSettingsUpdateProps | null>) =>
  useAdminAPI(props, ({ hotelID }) =>
    `/hotels/${hotelID}/settings/markup`)
    .put(computed<string>(() => JSON.stringify(
      getNullableRef<HotelMarkupSettingsUpdateProps, any>(
        props,
        (payload: HotelMarkupSettingsUpdateProps): any => ({ key: payload.key, value: payload.value }),
      ),
    )), 'application/json')
    .json<BaseResponse>()
