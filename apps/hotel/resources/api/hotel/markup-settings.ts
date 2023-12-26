import { computed } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { BaseResponse, useAdminAPI } from '~api'

import { getNullableRef } from '~lib/vue'

export type Percent = number
export type Time = `${number | ''}${number}:${number}${number}`
export type DateTime = string
export type CancelPeriodType = number

export interface TimePeriod {
  from: Time
  to: Time
}

export interface DatePeriod {
  from: DateTime
  to: DateTime
}

export interface RoomMarkupSettings {
  discount: Percent
}

export interface MarkupCondition {
  from: Time
  to: Time
  percent: Percent
}

export type NoCheckInMarkup = {
  percent: Percent
  value?: number
  valueType?: number
  cancelPeriodType: CancelPeriodType
}

export type DailyMarkup = {
  percent: Percent
  value?: number
  valueType?: number
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
  earlyCheckIn: MarkupCondition[]
  lateCheckOut: MarkupCondition[]
  cancelPeriods: CancelPeriod[]
}

type HotelMarkupSettingsProps = {
  hotelID: number
}

interface HotelRoomMarkupSettingsProps {
  hotelID: number
  roomID: number
}

export type HotelMarkupSettingsUpdateProps = {
  hotelID: number
  key: string
  value: string | number | MarkupCondition | DailyMarkup | CancelPeriod
}

export type HotelMarkupSettingsConditionAddProps = {
  hotelID: number
  key: string
  value: MarkupCondition | DailyMarkup | CancelPeriod
}

export type HotelMarkupSettingsConditionDeleteProps = {
  hotelID: number
  key: string
  index: number
}

export const useHotelMarkupSettingsAPI = () =>
  useAdminAPI({}, () => '/hotel/settings/markup')
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

export const useHotelRoomMarkupSettings = (props: MaybeRef<HotelRoomMarkupSettingsProps | null>) =>
  useAdminAPI(props, ({ hotelID, roomID }) => `/hotels/${hotelID}/rooms/${roomID}/settings/markup`)
    .get()
    .json<RoomMarkupSettings>()
