import { DateResponse } from '~api'
import { DailyMarkup, MarkupCondition, NoCheckInMarkup, Percent, Time } from '~api/hotel/markup-settings'

export interface HotelBookingGuest {
  id: number
  fullName: string
  countryId: number
  gender: number
  isAdult: boolean
  age?: number
}

export interface RoomInfo {
  id: number
  name: string
  guestsCount?: number
}

export interface HotelRoomBookingDetails {
  rateId: number
  isResident: boolean
  earlyCheckIn?: MarkupCondition
  lateCheckOut?: MarkupCondition
  guestNote?: string
  discount: Percent
}

export interface RoomBookingDayPrice {
  date: DateResponse
  baseValue: number
  grossValue: number
  netValue: number
  grossFormula: string
  netFormula: string
}

export interface RoomBookingPrice {
  dayPrices: RoomBookingDayPrice[]
  grossValue: number
  netValue: number
  netDayValue: number | null
  grossDayValue: number | null
}

export interface HotelRoomBooking {
  id: number
  roomInfo: RoomInfo
  guestIds: number[]
  details: HotelRoomBookingDetails
  price: RoomBookingPrice
}

export enum ExternalNumberTypeEnum {
  GotoStansBookingNumber = 1,
  HotelBookingNumber = 2,
  FullName = 3,
}

const ExternalNumberTypes = {
  GotoStansBookingNumber: ExternalNumberTypeEnum.GotoStansBookingNumber,
  HotelBookingNumber: ExternalNumberTypeEnum.HotelBookingNumber,
  FullName: ExternalNumberTypeEnum.FullName,
} as const

export type ExternalNumberType = typeof ExternalNumberTypes[keyof typeof ExternalNumberTypes]

export interface ExternalNumber {
  type: ExternalNumberType
  number: string | null
}

export interface CancelConditions {
  cancelNoFeeDate: DateResponse | null
  noCheckInMarkup: NoCheckInMarkup
  dailyMarkups: DailyMarkup[]
}

export interface BookingPeriod {
  dateFrom: DateResponse
  dateTo: DateResponse
  nightsCount: number
}

export interface HotelInfo {
  id: number
  name: string
  checkInTime: Time
  checkOutTime: Time
}

export interface HotelBookingDetails {
  id: number
  hotelInfo: HotelInfo
  period: BookingPeriod
  roomBookings: HotelRoomBooking[]
  externalNumber: ExternalNumber | null
  quotaProcessingMethod: number
}
