import { useAdminAPI } from '~api'

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
